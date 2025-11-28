<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Passenger;
use App\Models\Route;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:Admin|Agente']);
    }

    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $stats = [
            'total_revenue'   => Ticket::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'paid')->sum('price'),
            'total_tickets'   => Ticket::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'paid')->count(),
            'total_passengers'=> Passenger::whereHas('tickets', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid');
            })->count(),
            'avg_ticket'      => Ticket::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'paid')->avg('price') ?? 0,
        ];

        return view('admin.reports.index', compact('stats', 'startDate', 'endDate'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));

        $dailySales = Ticket::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(price) as total, COUNT(*) as tickets')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $routeSales = Ticket::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('schedule.route')
            ->selectRaw('schedule_id, COUNT(*) as tickets, SUM(price) as revenue')
            ->groupBy('schedule_id')
            ->with('schedule.route.originCity', 'schedule.route.destinationCity')
            ->get();

        return view('admin.reports.sales', compact('dailySales', 'routeSales', 'startDate', 'endDate'));
    }

    public function routes(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));

       $routes = Route::with(['originCity', 'destinationCity'])
        ->withCount([
            'schedules as schedules_count'
        ])
        ->leftJoin('schedules', 'routes.id', '=', 'schedules.route_id')
        ->leftJoin('tickets', function ($join) use ($startDate, $endDate) {
            $join->on('schedules.id', '=', 'tickets.schedule_id')
                 ->where('tickets.status', 'paid')
                 ->whereBetween('tickets.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })
        ->selectRaw('routes.*, 
                     COALESCE(COUNT(tickets.id), 0) as tickets_count,
                     COALESCE(SUM(tickets.price), 0) as tickets_sum_price')
        ->groupBy('routes.id')
        ->orderByDesc('tickets_count')
        ->get();

        return view('admin.reports.routes', compact('routes', 'startDate', 'endDate'));
    }

    public function passengers(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));

        $passengers = Passenger::withCount(['tickets' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid');
            }])
            ->withSum(['tickets' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'paid');
            }], 'price')
            ->orderByDesc('tickets_count')
            ->paginate(20);

        return view('admin.reports.passengers', compact('passengers', 'startDate', 'endDate'));
    }

    public function buses(Request $request)
    {
        $buses = Bus::with(['schedules' => function($q) {
                $q->where('departure_date', '>=', now()->subDays(30));
            }])
            ->withCount('schedules')
            ->get();

        return view('admin.reports.buses', compact('buses'));
    }

    // Exportações
    public function exportPdf(Request $request)
    {
        $type = $request->type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $data = $this->getReportData($type, $startDate, $endDate);
        $pdf = PDF::loadView("admin.reports.pdf.{$type}", $data);
        return $pdf->download("relatorio-{$type}-" . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $type = $request->type;
        return Excel::download(new SalesReportExport($request->all()), "relatorio-{$type}-" . now()->format('Y-m-d') . '.xlsx');
    }

    private function getReportData($type, $startDate, $endDate)
    {
        return match($type) {
            'sales' => $this->sales(new Request(['start_date' => $startDate, 'end_date' => $endDate])),
            'routes' => $this->routes(new Request(['start_date' => $startDate, 'end_date' => $endDate])),
            default => $this->index(new Request(['start_date' => $startDate, 'end_date' => $endDate])),
        };
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');

        $range = match($period) {
            '7days'  => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            '30days' => [now()->subDays(29)->startOfDay(), now()->endOfDay()],
            'month'  => [now()->startOfMonth(), now()->endOfMonth()],
            default  => [now()->startOfDay(), now()->endOfDay()], // hoje
        };

        [$start, $end] = $range;

        // === ESTATÍSTICAS RÁPIDAS ===
        $todayTickets = Ticket::where('status', 'paid')
            ->whereDate('created_at', today())
            ->count();

        $periodRevenue = Ticket::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->sum('price');

        $periodTickets = Ticket::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $newPassengers = Passenger::whereBetween('created_at', [$start, $end])
            ->count();

        // === GRÁFICO DE VENDAS (últimos 7 dias sempre) ===
        $salesData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Ticket::where('status', 'paid')
                ->whereDate('created_at', $date)
                ->count();
            $salesData->push([
                'day' => $date->format('D'),
                'date' => $date->format('d/m'),
                'tickets' => $count
            ]);
        }

        // === PRÓXIMAS PARTIDAS (próximas 24h) ===
        $upcomingSchedules = Schedule::with(['route.originCity', 'route.destinationCity', 'bus'])
            ->where('departure_date', '>=', today())
            ->where('departure_date', '<=', now()->addDay())
            ->where('status', 'active')
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->limit(10)
            ->get()
            ->map(function ($schedule) {
                $sold = $schedule->tickets()->whereIn('status', ['paid', 'validated'])->count();
                return (object)[
                    'schedule' => $schedule,
                    'tickets_count' => $sold,
                    'occupancy' => $schedule->bus->total_seats > 0 
                        ? round(($sold / $schedule->bus->total_seats) * 100, 1)
                        : 0
                ];
            });

        // === ROTAS MAIS POPULARES (este mês) ===
        $popularRoutes = Route::with(['originCity', 'destinationCity'])
            ->withCount(['schedules as trips_this_month' => fn($q) => $q->whereMonth('departure_date', now()->month)])
            ->leftJoin('schedules', 'routes.id', '=', 'schedules.route_id')
            ->leftJoin('tickets', function($join) {
                $join->on('schedules.id', '=', 'tickets.schedule_id')
                     ->where('tickets.status', 'paid');
            })
            ->whereMonth('schedules.departure_date', now()->month)
            ->selectRaw('routes.*, COALESCE(COUNT(tickets.id), 0) as tickets_count, COALESCE(SUM(tickets.price), 0) as revenue')
            ->groupBy('routes.id')
            ->orderByDesc('tickets_count')
            ->limit(5)
            ->get()
            ->map(function ($route) {
                $occupancy = $route->trips_this_month > 0
                    ? round(($route->tickets_count / ($route->trips_this_month * 40)) * 100, 1) // média 40 lugares
                    : 0;
                return (object)[
                    'route' => $route,
                    'tickets_count' => $route->tickets_count,
                    'revenue' => $route->revenue,
                    'occupancy' => $occupancy
                ];
            });

        return view('admin.dashboard.index', compact(
            'todayTickets',
            'periodRevenue',
            'periodTickets',
            'newPassengers',
            'salesData',
            'upcomingSchedules',
            'popularRoutes',
            'period'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
     /**
     * Display a listing of schedules
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['route.originCity', 'route.destinationCity', 'bus'])
            ->withCount(['tickets' => function ($q) {
                $q->whereIn('status', ['reserved', 'paid', 'validated']);
            }]);

        // Filtros
        if ($request->filled('search')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->whereHas('originCity', function ($sq) use ($request) {
                    $sq->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('destinationCity', function ($sq) use ($request) {
                    $sq->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('departure_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('departure_date', '<=', $request->date_to);
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'departure_date');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'departure_date') {
            $query->orderBy('departure_date', $sortOrder)
                  ->orderBy('departure_time', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $schedules = $query->paginate(15)->withQueryString();

        // Dados para filtros
        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('is_active', true)
            ->get();
        
        $buses = Bus::/*where('status', 'active')
            ->*/orderBy('registration_number')
            ->get();

        // Estatísticas
        $stats = [
            'total' => Schedule::count(),
            'today' => Schedule::whereDate('departure_date', today())->count(),
            'upcoming' => Schedule::where('status', 'active')
                ->where('departure_date', '>=', today())
                ->count(),
            'departed' => Schedule::where('status', 'departed')->count(),
        ];

        return view('admin.schedules.index', compact('schedules', 'routes', 'buses', 'stats'));
    }

    /**
     * Show the form for creating a new schedule
     */
    public function create()
    {
        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('is_active', true)
            ->get();
        
        $buses = Bus::/*where('status', 'active')
            ->*/orderBy('registration_number')
            ->get();

        return view('admin.schedules.create', compact('routes', 'buses'));
    }

    /**
     * Store a newly created schedule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'status' => 'sometimes|in:active,full,departed,cancelled',
        ]);

        // Verificar se o autocarro já está agendado para esta data/hora
        $conflict = Schedule::where('bus_id', $validated['bus_id'])
            ->where('departure_date', $validated['departure_date'])
            ->where('status', '!=', 'cancelled')
            ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, departure_time, ?)) < 180", [$validated['departure_time']])
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'bus_id' => 'Este autocarro já está agendado próximo a este horário (menos de 3 horas de diferença).'
            ])->withInput();
        }

        $validated['created_by_user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'active';

        $schedule = Schedule::create($validated);

        return redirect()
            ->route('schedules.show', $schedule)
            ->with('success', 'Horário criado com sucesso!');
    }

    /**
     * Display the specified schedule
     */
    public function show(Schedule $schedule)
    {
        $schedule->load([
            'route.originCity',
            'route.destinationCity',
            'bus',
            'tickets.passenger',
            'tickets.payment',
            'createdBy'
        ]);

        // Calcular estatísticas
        $totalSeats = $schedule->bus->total_seats;
        $bookedSeats = $schedule->tickets()
            ->whereIn('status', ['reserved', 'paid', 'validated'])
            ->count();
        
        $availableSeats = $totalSeats - $bookedSeats;
        $occupancyRate = $totalSeats > 0 ? ($bookedSeats / $totalSeats) * 100 : 0;

        // Receita
        $revenue = $schedule->tickets()
            ->whereIn('status', ['paid', 'validated'])
            ->sum('price');

        $expectedRevenue = $totalSeats * $schedule->price;

        $stats = [
            'total_seats' => $totalSeats,
            'booked_seats' => $bookedSeats,
            'available_seats' => $availableSeats,
            'occupancy_rate' => round($occupancyRate, 2),
            'revenue' => $revenue,
            'expected_revenue' => $expectedRevenue,
        ];

        return view('admin.schedules.show', compact('schedule', 'stats'));
    }

    /**
     * Show the form for editing the specified schedule
     */
    public function edit(Schedule $schedule)
    {
        // Não permitir edição de horários já partidos ou com bilhetes vendidos
        $hasTickets = $schedule->tickets()->whereIn('status', ['paid', 'validated'])->exists();
        
        if ($schedule->status === 'departed') {
            return back()->with('error', 'Não é possível editar um horário que já partiu.');
        }

        if ($hasTickets && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Este horário já possui bilhetes vendidos. Apenas administradores podem editá-lo.');
        }

        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('is_active', true)
            ->get();
        
        $buses = Bus::/*where('status', 'active')
            ->*/orderBy('registration_number')
            ->get();

        return view('admin.schedules.edit', compact('schedule', 'routes', 'buses', 'hasTickets'));
    }

    /**
     * Update the specified schedule
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'departure_date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,full,departed,cancelled',
        ]);

        // Verificar conflito de autocarro (exceto o próprio horário)
        $conflict = Schedule::where('bus_id', $validated['bus_id'])
            ->where('id', '!=', $schedule->id)
            ->where('departure_date', $validated['departure_date'])
            ->where('status', '!=', 'cancelled')
            ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, departure_time, ?)) < 180", [$validated['departure_time']])
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'bus_id' => 'Este autocarro já está agendado próximo a este horário.'
            ])->withInput();
        }

        // Se mudar o autocarro, verificar se o novo tem capacidade suficiente
        if ($validated['bus_id'] != $schedule->bus_id) {
            $newBus = Bus::find($validated['bus_id']);
            $bookedSeats = $schedule->tickets()
                ->whereIn('status', ['reserved', 'paid', 'validated'])
                ->count();

            if ($bookedSeats > $newBus->total_seats) {
                return back()->withErrors([
                    'bus_id' => "Este autocarro tem apenas {$newBus->total_seats} lugares, mas já existem {$bookedSeats} bilhetes vendidos."
                ])->withInput();
            }
        }

        $schedule->update($validated);

        return redirect()
            ->route('schedules.show', $schedule)
            ->with('success', 'Horário atualizado com sucesso!');
    }

    /**
     * Remove the specified schedule
     */
    public function destroy(Schedule $schedule)
    {
        // Verificar se há bilhetes vendidos
        $hasActiveTickets = $schedule->tickets()
            ->whereIn('status', ['paid', 'validated'])
            ->exists();

        if ($hasActiveTickets) {
            return back()->with('error', 'Não é possível excluir este horário pois existem bilhetes vendidos. Cancele o horário ao invés de excluí-lo.');
        }

        // Cancelar bilhetes reservados antes de excluir
        $schedule->tickets()
            ->where('status', 'reserved')
            ->update(['status' => 'cancelled']);

        $schedule->delete();

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Horário excluído com sucesso!');
    }

    /**
     * Cancel a schedule
     */
    public function cancel(Schedule $schedule)
    {
        if ($schedule->status === 'cancelled') {
            return back()->with('error', 'Este horário já está cancelado.');
        }

        DB::beginTransaction();
        try {
            // Atualizar status do horário
            $schedule->update(['status' => 'cancelled']);

            // Cancelar todos os bilhetes pendentes e reservados
            $schedule->tickets()
                ->whereIn('status', ['reserved'])
                ->update(['status' => 'cancelled']);

            // TODO: Processar reembolsos para bilhetes pagos
            // TODO: Enviar notificações para passageiros

            DB::commit();

            return back()->with('success', 'Horário cancelado com sucesso. Os passageiros serão notificados.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cancelar horário: ' . $e->getMessage());
        }
    }

    /**
     * View seats layout and availability
     */
    public function seats(Schedule $schedule)
    {
        $schedule->load(['bus', 'tickets']);

        // Obter layout de assentos do autocarro
        $seatLayout = $schedule->bus->seat_layout ?? $this->generateDefaultSeatLayout($schedule->bus->total_seats);

        // Marcar assentos ocupados
        $occupiedSeats = $schedule->tickets()
            ->whereIn('status', ['reserved', 'paid', 'validated'])
            ->pluck('seat_number')
            ->toArray();

        return view('admin.schedules.seats', compact('schedule', 'seatLayout', 'occupiedSeats'));
    }

    /**
     * Generate default seat layout
     */
    private function generateDefaultSeatLayout($totalSeats)
    {
        $layout = [];
        $seatsPerRow = 4; // 2 assentos de cada lado do corredor
        $rows = ceil($totalSeats / $seatsPerRow);

        $seatNumber = 1;
        for ($row = 1; $row <= $rows; $row++) {
            $rowSeats = [];
            for ($col = 1; $col <= $seatsPerRow && $seatNumber <= $totalSeats; $col++) {
                $rowSeats[] = [
                    'number' => (string)$seatNumber,
                    'type' => 'standard',
                    'position' => $col <= 2 ? 'left' : 'right'
                ];
                $seatNumber++;
            }
            $layout[] = $rowSeats;
        }

        return $layout;
    }

    /**
     * API: Get available schedules for a route
     */
    public function getAvailableSchedules(Request $request)
    {
        $query = Schedule::with(['route', 'bus'])
            ->where('status', 'active')
            ->where('departure_date', '>=', today());

        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('departure_date', $request->date);
        }

        $schedules = $query->get()->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'departure_date' => $schedule->departure_date->format('d/m/Y'),
                'departure_time' => $schedule->departure_time,
                'price' => $schedule->price,
                'available_seats' => $schedule->availableSeats(),
                'bus_model' => $schedule->bus->model,
            ];
        });

        return response()->json($schedules);
    }

    /**
     * API: Check seat availability
     */
    public function checkSeatAvailability(Schedule $schedule, $seatNumber)
    {
        $isOccupied = $schedule->tickets()
            ->where('seat_number', $seatNumber)
            ->whereIn('status', ['reserved', 'paid', 'validated'])
            ->exists();

        return response()->json([
            'available' => !$isOccupied,
            'seat_number' => $seatNumber
        ]);
    }
}

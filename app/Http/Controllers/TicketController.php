<?php

namespace App\Http\Controllers;

use App\Mail\TicketPurchased;
use App\Models\Passenger;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
      /**
     * Display a listing of tickets
     */
    public function index(Request $request)
    {
        $query = Ticket::with([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus',
            'validatedBy'
        ]);

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por data
        if ($request->filled('date')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->whereDate('departure_date', $request->date);
            });
        }

        // Filtro por data range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->whereBetween('departure_date', [$request->date_from, $request->date_to]);
            });
        }

        // Filtro por horário específico
        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }

        // Filtro por rota
        if ($request->filled('route_id')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->where('route_id', $request->route_id);
            });
        }

        // Busca por número do bilhete
        if ($request->filled('ticket_number')) {
            $query->where('ticket_number', 'like', '%' . $request->ticket_number . '%');
        }

        // Busca por passageiro (nome, email, telefone)
        if ($request->filled('passenger_search')) {
            $search = $request->passenger_search;
            $query->whereHas('passenger', function($q) use ($search) {
                $q->where(function($subQ) use ($search) {
                    $subQ->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                });
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $tickets = $query->paginate(20)->withQueryString();

        // Dados para filtros
        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        $schedules = Schedule::with(['route.originCity', 'route.destinationCity'])
            ->where('departure_date', '>=', today())
            ->where('status', 'active')
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->limit(50)
            ->get();

        // Estatísticas
        $stats = [
            'total' => Ticket::count(),
            'today' => Ticket::whereHas('schedule', function($q) {
                $q->whereDate('departure_date', today());
            })->count(),
            'reserved' => Ticket::where('status', 'reserved')->count(),
            'paid' => Ticket::where('status', 'paid')->count(),
            'validated' => Ticket::where('status', 'validated')->count(),
            'cancelled' => Ticket::where('status', 'cancelled')->count(),
            'revenue_today' => Ticket::whereHas('schedule', function($q) {
                $q->whereDate('departure_date', today());
            })->whereIn('status', ['paid', 'validated'])->sum('price'),
            'revenue_month' => Ticket::whereMonth('created_at', now()->month)
                ->whereIn('status', ['paid', 'validated'])
                ->sum('price'),
        ];

        return view('admin.tickets.index', compact('tickets', 'routes', 'schedules', 'stats'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create(Request $request)
    {
        // Se vier schedule_id e seat da URL (vindo da seleção de assentos)
        $preselectedScheduleId = $request->get('schedule_id');
        $preselectedSeat = $request->get('seat');

        // Horários disponíveis
        $schedules = Schedule::with(['route.originCity', 'route.destinationCity', 'bus'])
            ->where('departure_date', '>=', today())
            ->where('status', 'active')
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        // Rotas ativas (para filtro)
        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('is_active', true)
            ->get();

        return view('admin.tickets.create', compact(
            'schedules',
            'routes',
            'preselectedScheduleId',
            'preselectedSeat'
        ));
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_number' => 'required|string',
            'passenger_id' => 'nullable|exists:passengers,id',
            
            // Se criar novo passageiro
            'first_name' => 'required_without:passenger_id|string|max:100',
            'last_name' => 'required_without:passenger_id|string|max:100',
            'email' => 'required_without:passenger_id|email|max:255',
            'phone' => 'required_without:passenger_id|string|max:20',
            'document_type' => 'required_without:passenger_id|in:bi,passport,birth_certificate',
            'document_number' => 'required_without:passenger_id|string|max:50',
            
            'payment_method' => 'required|in:cash,mpesa,emola,card',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:reserved,paid',
            'send_email' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $schedule = Schedule::with(['route', 'bus'])->findOrFail($validated['schedule_id']);

            // Verificar se o assento está disponível
            $seatTaken = Ticket::where('schedule_id', $schedule->id)
                ->where('seat_number', $validated['seat_number'])
                ->whereIn('status', ['reserved', 'paid', 'validated'])
                ->exists();

            if ($seatTaken) {
                return back()->withErrors([
                    'seat_number' => 'Este assento já está ocupado.'
                ])->withInput();
            }

            // Obter ou criar passageiro
            if ($validated['passenger_id']) {
                $passenger = Passenger::findOrFail($validated['passenger_id']);
            } else {
                $passenger = Passenger::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'document_type' => $validated['document_type'],
                    'document_number' => $validated['document_number'],
                    'is_active' => true,
                    'password'=>Hash::make('12345678') //Hash::make(uniqid())
                ]);
            }

            $ticketNumber = Ticket::generateTicketNumber();
            // Gerar QR Code
            $qrCode = $this->generateQrCode($ticketNumber);

            // Criar ticket
            $ticket = Ticket::create([
                'ticket_number' => $ticketNumber,
                'passenger_id' => $passenger->id,
                'schedule_id' => $schedule->id,
                'seat_number' => $validated['seat_number'],
                'price' => $validated['price'],
                'status' => $validated['status'],
                'qr_code' => $qrCode,
            ]);

            // Log da criação
            Log::info('Bilhete criado manualmente pelo admin', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'created_by' => Auth::id(),
                'passenger_id' => $passenger->id,
                'schedule_id' => $schedule->id,
            ]);

            // Enviar email se solicitado
            if ($request->boolean('send_email', true)) {
                try {
                    Mail::to($passenger->email)->send(new TicketPurchased($ticket));
                    Log::info('Email de bilhete enviado', ['ticket_id' => $ticket->id]);
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar email de bilhete', [
                        'ticket_id' => $ticket->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', "Bilhete {$ticket->ticket_number} emitido com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar bilhete', ['error' => $e->getMessage()]);
            
            return back()
                ->withErrors(['error' => 'Erro ao criar bilhete: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus',
            'validatedBy',
            'payment'
        ]);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket (limitado)
     */
    public function edit(Ticket $ticket)
    {
        // Permitir editar apenas alguns campos e apenas se não foi validado
        if ($ticket->status === 'validated') {
            return back()->with('error', 'Não é possível editar um bilhete já validado.');
        }

        if ($ticket->status === 'cancelled') {
            return back()->with('error', 'Não é possível editar um bilhete cancelado.');
        }

        $ticket->load(['passenger', 'schedule.route', 'schedule.bus']);

        return view('admin.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified ticket (limitado)
     */
    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'validated') {
            return back()->with('error', 'Não é possível editar um bilhete já validado.');
        }

        $validated = $request->validate([
            'status' => 'required|in:reserved,paid,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $ticket->update([
                'status' => $validated['status']
            ]);

            Log::info('Bilhete atualizado', [
                'ticket_id' => $ticket->id,
                'updated_by' => Auth::id(),
                'new_status' => $validated['status']
            ]);

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', 'Bilhete atualizado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar bilhete', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao atualizar bilhete.');
        }
    }

    /**
     * Cancel the specified ticket
     */
    public function cancel(Request $request, Ticket $ticket)
    {
        if (!$ticket->isCancellable()) {
            return back()->with('error', 'Este bilhete não pode ser cancelado.');
        }

        try {
            DB::beginTransaction();

            $ticket->update([
                'status' => 'cancelled'
            ]);

            // Log do cancelamento
            Log::warning('Bilhete cancelado', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'cancelled_by' => Auth::id(),
                'reason' => $request->input('reason', 'Não especificado')
            ]);

            // TODO: Processar reembolso se necessário
            // TODO: Enviar email de cancelamento

            DB::commit();

            return back()->with('success', 'Bilhete cancelado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cancelar bilhete', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao cancelar bilhete.');
        }
    }

    /**
     * Validate ticket (mark as used)
     */
    public function validateTicket(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'validated') {
            return response()->json([
                'success' => false,
                'message' => 'Este bilhete já foi validado em ' . $ticket->validated_at->format('d/m/Y H:i')
            ], 422);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Este bilhete foi cancelado e não pode ser usado.'
            ], 422);
        }

        if ($ticket->status === 'reserved') {
            return response()->json([
                'success' => false,
                'message' => 'Este bilhete está reservado. Confirme o pagamento primeiro.'
            ], 422);
        }

        // Verificar se é da viagem de hoje
        if (!$ticket->schedule->departure_date->isToday()) {
            return response()->json([
                'success' => false,
                'message' => 'Este bilhete não é para a viagem de hoje.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $ticket->update([
                'status' => 'validated',
                'validated_at' => now(),
                'validated_by_user_id' => Auth::id()
            ]);

            Log::info('Bilhete validado', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'validated_by' => Auth::id(),
                'validated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bilhete validado com sucesso!',
                'ticket' => [
                    'ticket_number' => $ticket->ticket_number,
                    'passenger_name' => $ticket->passenger->first_name . ' ' . $ticket->passenger->last_name,
                    'seat_number' => $ticket->seat_number,
                    'route' => $ticket->schedule->route->originCity->name . ' → ' . $ticket->schedule->route->destinationCity->name
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao validar bilhete', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao validar bilhete.'
            ], 500);
        }
    }

    /**
     * Resend ticket email
     */
    public function resendEmail(Ticket $ticket)
    {
        try {
            $ticket->load([
                'passenger',
                'schedule.route.originCity',
                'schedule.route.destinationCity',
                'schedule.bus'
            ]);

            Mail::to($ticket->passenger->email)->send(new TicketPurchased($ticket));

            Log::info('Email de bilhete reenviado', [
                'ticket_id' => $ticket->id,
                'email' => $ticket->passenger->email,
                'resent_by' => Auth::id()
            ]);

            return back()->with('success', 'Email reenviado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao reenviar email', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao reenviar email.');
        }
    }

    /**
     * Generate QR Code
     * Mesmo algoritmo usado na parte pública
     */
    private function generateQrCode($ticketNumber)
    {
        $timestamp = now()->timestamp;
        $data = $ticketNumber . '|' . $timestamp;
        
        // Adicionar hash HMAC SHA256 para segurança
        $hash = hash_hmac('sha256', $data, config('app.key'));
        
        // Formato final: TICKET|TIMESTAMP|HASH
        $fullData = $data . '|' . $hash;
        
        // Codificar em Base64 para QR Code
        return base64_encode($fullData);
    }

    /**
     * Get available seats for a schedule (API)
     */
    public function getAvailableSeats(Schedule $schedule)
    {
        $occupiedSeats = Ticket::where('schedule_id', $schedule->id)
            ->whereIn('status', ['reserved', 'paid', 'validated'])
            ->pluck('seat_number')
            ->toArray();

        $totalSeats = $schedule->bus->total_seats;
        $availableSeats = $totalSeats - count($occupiedSeats);

        return response()->json([
            'total_seats' => $totalSeats,
            'occupied_seats' => $occupiedSeats,
            'available_seats' => $availableSeats
        ]);
    }


    
    /**
     * API: Get available schedules (for create form)
     */
    public function apiGetSchedules(Request $request)
    {
        $query = Schedule::with(['route.originCity', 'route.destinationCity', 'bus'])
            ->where('status', 'active')
            ->where('departure_date', $request->date);

        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        $schedules = $query->orderBy('departure_time')->get()->map(function ($schedule) {
            $availableSeats = $schedule->availableSeats();
            
            return [
                'id' => $schedule->id,
                'route' => $schedule->route->originCity->name . ' → ' . $schedule->route->destinationCity->name,
                'time' => $schedule->departure_time,
                'price' => number_format($schedule->price, 2),
                'bus' => $schedule->bus->model . ' (' . $schedule->bus->registration_number . ')',
                'available_seats' => $availableSeats,
                'total_seats' => $schedule->bus->total_seats,
            ];
        });

        return response()->json($schedules);
    }

    /**
     * API: Search passengers (for create form)
     */
    public function apiSearchPassengers(Request $request)
    {
        $search = $request->get('q');

        if (!$search || strlen($search) < 3) {
            return response()->json([]);
        }

        $passengers = Passenger::where(function($query) use ($search) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        })
        ->where('is_active', true)
        ->limit(10)
        ->get()
        ->map(function($passenger) {
            return [
                'id' => $passenger->id,
                'first_name' => $passenger->first_name,
                'last_name' => $passenger->last_name,
                'email' => $passenger->email,
                'phone' => $passenger->phone,
                'document_type' => $passenger->document_type,
                'document_number' => $passenger->document_number,
            ];
        });

        return response()->json($passengers);
    }
}

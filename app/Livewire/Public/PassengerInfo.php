<?php

namespace App\Livewire\Public;

use App\Models\Passenger;
use App\Models\Schedule;
use App\Models\TemporaryReservation;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class PassengerInfo extends Component
{
     public $schedule;
    public $seats = [];
    public $totalPrice = 0;
    
    // Dados do passageiro
    public $passengers = [];
    public $email;
    public $phone;
    public $accept_terms = false;
    public $create_account = false;
    public $password;
    
    // Pagamento
    public $payment_method = 'mpesa';
    public $mpesa_number;
    
    // Estados
    public $step = 1; // 1: dados, 2: pagamento, 3: processando
    public $reservationExpiry;

    protected function rules()
    {
        $rules = [
            'email' => 'required|email',
            'phone' => 'required|regex:/^(\+258|258)?[8][2-7][0-9]{7}$/',
            'accept_terms' => 'accepted',
            'payment_method' => 'required|in:mpesa,emola,cash',
        ];

        // Regras para cada passageiro
        foreach ($this->passengers as $index => $passenger) {
            $rules["passengers.{$index}.first_name"] = 'required|string|min:2|max:100';
            $rules["passengers.{$index}.last_name"] = 'required|string|min:2|max:100';
            $rules["passengers.{$index}.document_type"] = 'required|in:bi,passport,birth_certificate';
            $rules["passengers.{$index}.document_number"] = 'required|string|max:50';
        }

        // Regras para criação de conta
        if ($this->create_account) {
            $rules['password'] = 'required|min:8';
        }

        // Regras para M-Pesa
        if ($this->payment_method === 'mpesa') {
            $rules['mpesa_number'] = 'required|regex:/^(\+258|258)?[8][2-7][0-9]{7}$/';
        }

        return $rules;
    }

    protected $messages = [
        'email.required' => 'O email é obrigatório',
        'email.email' => 'Digite um email válido',
        'phone.required' => 'O telefone é obrigatório',
        'phone.regex' => 'Digite um número de telefone moçambicano válido (ex: 84 123 4567)',
        'accept_terms.accepted' => 'Você deve aceitar os termos e condições',
        'mpesa_number.required' => 'Digite o número M-Pesa',
        'mpesa_number.regex' => 'Digite um número M-Pesa válido',
        'passengers.*.first_name.required' => 'O primeiro nome é obrigatório',
        'passengers.*.last_name.required' => 'O último nome é obrigatório',
        'passengers.*.document_type.required' => 'Selecione o tipo de documento',
        'passengers.*.document_number.required' => 'O número do documento é obrigatório',
    ];

    public function mount(Schedule $schedule)
    {
        $this->schedule = $schedule->load(['route.originCity', 'route.destinationCity', 'bus']);
        
        // Obter assentos da URL
        $seatsParam = request('seats');
        if (!$seatsParam) {
            return redirect()->route('public.seats', ['schedule' => $schedule->id]);
        }

        $this->seats = explode(',', $seatsParam);
        
        // Verificar se assentos ainda estão disponíveis
        $this->validateSeatsAvailability();
        
        // Calcular preço total
        $this->totalPrice = count($this->seats) * $this->schedule->price;
        
        // Inicializar array de passageiros
        foreach ($this->seats as $seat) {
            $this->passengers[] = [
                'seat_number' => $seat,
                'first_name' => '',
                'last_name' => '',
                'document_type' => 'bi',
                'document_number' => '',
            ];
        }
        
        // Timer de expiração
        $this->reservationExpiry = now()->addMinutes(15)->timestamp;
        
        // Preencher dados se usuário autenticado
        if (auth()->check()) {
            $user = auth()->user();
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';
        }
    }

    public function validateSeatsAvailability()
    {
        $occupiedSeats = $this->schedule->tickets()
            ->whereIn('status', ['reserved', 'paid', 'validated'])
            ->pluck('seat_number')
            ->toArray();

        $unavailableSeats = array_intersect($this->seats, $occupiedSeats);

        if (!empty($unavailableSeats)) {
            session()->flash('error', 'Alguns assentos não estão mais disponíveis.');
            return redirect()->route('public.seats', ['schedule' => $this->schedule->id]);
        }
    }

    public function goToPayment()
    {
        $this->validate();
        $this->step = 2;
    }

    public function backToInfo()
    {
        $this->step = 1;
    }

    public function processPayment()
    {
        $this->validate();
        
        $this->step = 3; // Processando

        try {
            DB::beginTransaction();

            // 1. Criar ou buscar passageiros
            $createdPassengers = [];
            foreach ($this->passengers as $passengerData) {
                $passenger = Passenger::firstOrCreate(
                    [
                        'email' => $this->email,
                        'phone' => $this->phone,
                    ],
                    [
                        'first_name' => $passengerData['first_name'],
                        'last_name' => $passengerData['last_name'],
                        'document_type' => $passengerData['document_type'],
                        'document_number' => $passengerData['document_number'],
                        'password' => $this->create_account && $this->password 
                            ? Hash::make($this->password) 
                            : Hash::make(uniqid()),
                    ]
                );

                $createdPassengers[] = [
                    'passenger' => $passenger,
                    'seat' => $passengerData['seat_number']
                ];
            }

            // 2. Criar tickets
            $tickets = [];
            foreach ($createdPassengers as $data) {
                $ticket = Ticket::create([
                    'ticket_number' => Ticket::generateTicketNumber(),
                    'passenger_id' => $data['passenger']->id,
                    'schedule_id' => $this->schedule->id,
                    'seat_number' => $data['seat'],
                    'price' => $this->schedule->price,
                    'status' => $this->payment_method === 'cash' ? 'reserved' : 'pending_payment',
                    'qr_code' => null, // Será gerado após pagamento
                ]);

                $tickets[] = $ticket;
            }

            // 3. Processar pagamento
            if ($this->payment_method === 'mpesa') {
                // Integração M-Pesa (simulado)
                $paymentResult = $this->processMpesaPayment();
                
                if ($paymentResult['success']) {
                    // Atualizar status dos tickets para 'paid'
                    foreach ($tickets as $ticket) {
                        $ticket->update([
                            'status' => 'paid',
                            'qr_code' => $this->generateQrCode($ticket->ticket_number),
                        ]);
                    }
                }
            } elseif ($this->payment_method === 'cash') {
                // Pagamento em dinheiro - tickets ficam como 'reserved'
                foreach ($tickets as $ticket) {
                    $ticket->update([
                        'qr_code' => $this->generateQrCode($ticket->ticket_number),
                    ]);
                }
            }

            // 4. Remover reservas temporárias
            TemporaryReservation::where('schedule_id', $this->schedule->id)
                ->whereIn('seat_number', $this->seats)
                ->delete();

            // 5. Enviar emails/SMS com bilhetes
            $this->sendTicketNotifications($tickets);

            DB::commit();

            // Redirecionar para confirmação
            session()->flash('success', 'Compra realizada com sucesso!');
            return redirect()->route('public.ticket-confirmation', [
                'tickets' => collect($tickets)->pluck('id')->implode(',')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->step = 2;
            $this->dispatch('show-toast', [
                'message' => 'Erro ao processar pagamento: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    private function processMpesaPayment()
    {
        // TODO: Integração real com M-Pesa API
        // Por enquanto, simulação
        sleep(2); // Simular delay de processamento
        
        return [
            'success' => true,
            'transaction_id' => 'MPESA-' . time(),
        ];
    }

    private function generateQrCode($ticketNumber)
    {
        // Gerar código QR simples (pode usar biblioteca específica)
        return base64_encode($ticketNumber . '|' . now()->timestamp);
    }

    private function sendTicketNotifications($tickets)
    {
        // TODO: Enviar emails e SMS com os bilhetes
        // Por enquanto, apenas log
        \Log::info('Tickets enviados para ' . $this->email, [
            'tickets' => collect($tickets)->pluck('ticket_number')->toArray()
        ]);
    }

    public function render()
    {
        return view('livewire.public.passenger-info')
            ->layout('layouts.passenger');
    }
    // public function render()
    // {
    //     return view('livewire.public.passenger-info');
    // }
}

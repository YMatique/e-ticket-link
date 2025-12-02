<?php

namespace App\Livewire\Public;

use App\Models\Passenger;
use App\Models\Schedule;
use App\Models\TemporaryReservation;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'phone' => ['required', 'regex:/^(\+?258)?[8][2-7][0-9]{7}$/'],
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
            $rules['mpesa_number'] = ['required', 'regex:/^(\+?258)?[8][2-7][0-9]{7}$/'];
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
        if (! $seatsParam) {
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

        if (! empty($unavailableSeats)) {
            session()->flash('error', 'Alguns assentos não estão mais disponíveis.');

            return redirect()->route('public.seats', ['schedule' => $this->schedule->id]);
        }
    }

    public function goToPayment()
    {
        // $this->validate();
        $rules = [
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^(\+?258)?[8][2-7][0-9]{7}$/'],
            'accept_terms' => 'accepted',
        ];

        // Validar dados dos passageiros
        foreach ($this->passengers as $index => $passenger) {
            $rules["passengers.{$index}.first_name"] = 'required|string|min:2|max:100';
            $rules["passengers.{$index}.last_name"] = 'required|string|min:2|max:100';
            $rules["passengers.{$index}.document_type"] = 'required|in:bi,passport,birth_certificate';
            $rules["passengers.{$index}.document_number"] = 'required|string|max:50';
        }

        // Validar senha se criar conta
        if ($this->create_account) {
            $rules['password'] = 'required|min:8';
        }

        // Validar apenas Step 1
        $this->validate($rules);
        $this->step = 2;
    }

    public function backToInfo()
    {
        $this->step = 1;
    }

    public function processPayment()
    {
        // $this->validate();
        // Validar apenas campos do Step 2

        $rules = [
            'payment_method' => 'required|in:mpesa,emola,cash',
        ];

        // Validar M-Pesa apenas se for o método escolhido
        if ($this->payment_method === 'mpesa') {
            $rules['mpesa_number'] = ['required', 'regex:/^(\+?258)?[8][2-7][0-9]{7}$/'];
        }

        $this->validate($rules);

        $this->step = 3; // Processando

        try {
            DB::beginTransaction();
            $account = Auth::guard('account')->user();
            $accountId = $account ? $account->id : null;

            // 1. Criar ou buscar passageiros
            $createdPassengers = [];
            foreach ($this->passengers as $passengerData) {
                $passenger = Passenger::firstOrCreate(
                    [
                        'document_type' => $passengerData['document_type'],
                        'document_number' => $passengerData['document_number'],
                    ],
                    [
                        'first_name' => $passengerData['first_name'],
                        'last_name' => $passengerData['last_name'],
                        'document_type' => $passengerData['document_type'],
                        'document_number' => $passengerData['document_number'],
                        'password' => $this->create_account && $this->password
                            ? Hash::make($this->password)
                            : Hash::make(uniqid()),
                        'account_id' => $accountId,
                    ]
                );

                if ($accountId && ! $passenger->account_id) {
                    $passenger->update(['account_id' => $accountId]);
                }
                $createdPassengers[] = [
                    'passenger' => $passenger,
                    'seat' => $passengerData['seat_number'],
                ];
            }

            // 2. Criar tickets
            $tickets = [];
            foreach ($createdPassengers as $data) {
                $ticket = Ticket::create([
                    'ticket_number' => Ticket::generateTicketNumber(),
                    'passenger_id' => $data['passenger']->id,
                    'schedule_id' => $this->schedule->id,
                     'account_id' => $accountId,
                    'seat_number' => $data['seat'],
                    'price' => $this->schedule->price,
                    'status' => 'reserved', // $this->payment_method === 'cash' ? 'reserved' : 'pending_payment',
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
                'tickets' => collect($tickets)->pluck('id')->implode(','),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('ERRO: '.$e->getMessage());

            $this->step = 2;
            $this->dispatch('show-toast', [
                'message' => 'Erro ao processar pagamento: '.$e->getMessage(),
                'type' => 'error',
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
            'transaction_id' => 'MPESA-'.time(),
        ];
    }

    private function generateQrCode($ticketNumber)
    {
        // Gerar código QR simples (pode usar biblioteca específica)
        // return base64_encode($ticketNumber . '|' . now()->timestamp); //LEGACY
        $timestamp = now()->timestamp;
        $data = $ticketNumber.'|'.$timestamp;

        // Adicionar hash HMAC SHA256 para segurança (previne falsificação)
        $hash = hash_hmac('sha256', $data, config('app.key'));

        // Formato final: TICKET|TIMESTAMP|HASH
        $fullData = $data.'|'.$hash;

        // Codificar em Base64 para QR Code
        return base64_encode($fullData);
    }

    /**
     * Valida um QR Code decodificando e verificando o hash
     *
     * @param  string  $qrCode
     * @return array|false Retorna ['ticket_number' => string, 'timestamp' => int] ou false se inválido
     */
    private function validateQrCode($qrCode)
    {
        try {
            // Decodificar Base64
            $decoded = base64_decode($qrCode, true);

            if ($decoded === false) {
                return false;
            }

            // Separar componentes
            $parts = explode('|', $decoded);

            if (count($parts) !== 3) {
                return false;
            }

            [$ticketNumber, $timestamp, $hash] = $parts;

            // Verificar hash de segurança
            $expectedHash = hash_hmac('sha256', $ticketNumber.'|'.$timestamp, config('app.key'));

            if (! hash_equals($expectedHash, $hash)) {
                \Log::warning('QR Code com hash inválido detectado', [
                    'qr_code' => substr($qrCode, 0, 50).'...',
                ]);

                return false;
            }

            return [
                'ticket_number' => $ticketNumber,
                'timestamp' => (int) $timestamp,
                'valid' => true,
            ];

        } catch (\Exception $e) {
            \Log::error('Erro ao validar QR Code', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function sendTicketNotifications($tickets)
    {
        foreach ($tickets as $ticket) {
            try {
                // Enviar email
                Mail::to($ticket->passenger->email)->send(
                    new \App\Mail\TicketPurchased($ticket)
                );

                \Log::info('Email de bilhete enviado com sucesso', [
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'email' => $ticket->passenger->email,
                ]);

            } catch (\Exception $e) {
                // Log do erro mas não falha a compra
                \Log::error('Erro ao enviar email de bilhete', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // TODO: Enviar SMS (futuro)
        // TODO: Enviar WhatsApp (futuro)

        // Por enquanto, apenas log
        \Log::info('Tickets enviados para '.$this->email, [
            'tickets' => collect($tickets)->pluck('ticket_number')->toArray(),
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

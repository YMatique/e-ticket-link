<?php

namespace App\Livewire\Public;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ValidateTicket extends Component
{
    public $ticket_code = '';
    public $ticket = null;
    public $searched = false;
    public $validation_message = '';
    public $validation_type = ''; // success, error, warning
    
    // Stats
    public $stats = [
        'validated_today' => 0,
        'pending_validation' => 0,
        'no_show' => 0,
    ];

    protected $rules = [
        'ticket_code' => 'required|string|min:3',
    ];

    protected $messages = [
        'ticket_code.required' => 'Digite o número do bilhete ou escaneie o QR Code',
        'ticket_code.min' => 'Digite pelo menos 3 caracteres',
    ];

    public function mount()
    {
        // Carregar estatísticas se usuário autenticado
        if (Auth::check()) {
            $this->loadStats();
        }
    }

    public function updatedTicketCode()
    {
        // Auto-buscar quando digitar número completo (começando com TKT-)
        if (strlen($this->ticket_code) >= 15 && str_starts_with(strtoupper($this->ticket_code), 'TKT-')) {
            $this->searchTicket();
        }
    }

    public function searchTicket()
    {
        $this->validate();

        $this->searched = true;
        $this->ticket = null;
        $this->validation_message = '';

        // Limpar código (remover espaços, etc)
        $cleanCode = trim(strtoupper($this->ticket_code));

        // Buscar ticket
        $ticket = Ticket::with([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus'
        ])
        ->where('ticket_number', $cleanCode)
        ->orWhere('qr_code', $cleanCode)
        ->first();

        if (!$ticket) {
            $this->validation_type = 'error';
            $this->validation_message = 'Bilhete não encontrado. Verifique o número e tente novamente.';
            return;
        }

        $this->ticket = $ticket;

        // Verificar status
        $this->checkTicketStatus();
    }

    private function checkTicketStatus()
    {
        // Verificar se já foi validado
        if ($this->ticket->status === 'validated') {
            $this->validation_type = 'warning';
            $this->validation_message = 'Este bilhete já foi validado em ' . 
                $this->ticket->validated_at->format('d/m/Y H:i') . '.';
            return;
        }

        // Verificar se foi cancelado
        if ($this->ticket->status === 'cancelled') {
            $this->validation_type = 'error';
            $this->validation_message = 'Este bilhete foi CANCELADO e não pode ser usado.';
            return;
        }

        // Verificar se foi pago (ou reservado para pagamento em dinheiro)
        if ($this->ticket->status === 'reserved') {
            $this->validation_type = 'warning';
            $this->validation_message = 'Atenção: Este bilhete está RESERVADO. Confirme o pagamento antes de permitir o embarque.';
            return;
        }

        // Verificar se a viagem é hoje
        $scheduleDate = $this->ticket->schedule->departure_date;
        if (!$scheduleDate->isToday()) {
            if ($scheduleDate->isPast()) {
                $this->validation_type = 'error';
                $this->validation_message = 'Este bilhete é de uma viagem PASSADA (' . 
                    $scheduleDate->format('d/m/Y') . ').';
            } else {
                $this->validation_type = 'warning';
                $this->validation_message = 'Este bilhete é para ' . 
                    $scheduleDate->format('d/m/Y') . ', não hoje.';
            }
            return;
        }

        // Tudo OK!
        $this->validation_type = 'success';
        $this->validation_message = 'Bilhete VÁLIDO! Pronto para embarque.';
    }

    public function validateTicket()
    {
        if (!$this->ticket) {
            session()->flash('error', 'Nenhum bilhete selecionado.');
            return;
        }

        if ($this->ticket->status === 'validated') {
            session()->flash('warning', 'Este bilhete já foi validado anteriormente.');
            return;
        }

        if ($this->ticket->status === 'cancelled') {
            session()->flash('error', 'Não é possível validar um bilhete cancelado.');
            return;
        }

        // Validar bilhete
        $this->ticket->update([
            'status' => 'validated',
            'validated_at' => now(),
            'validated_by_user_id' => Auth::id(),
        ]);

        session()->flash('success', 'Bilhete validado com sucesso! Passageiro pode embarcar.');

        // Recarregar stats
        $this->loadStats();

        // Limpar pesquisa
        $this->reset(['ticket_code', 'ticket', 'searched', 'validation_message', 'validation_type']);
    }

    public function cancelValidation()
    {
        $this->reset(['ticket_code', 'ticket', 'searched', 'validation_message', 'validation_type']);
    }

    private function loadStats()
    {
        $today = now()->toDateString();

        $this->stats['validated_today'] = Ticket::whereDate('validated_at', $today)->count();
        
        $this->stats['pending_validation'] = Ticket::whereHas('schedule', function($query) use ($today) {
            $query->whereDate('departure_date', $today);
        })
        ->whereIn('status', ['paid', 'reserved'])
        ->count();

        // No-show: bilhetes pagos de viagens passadas que não foram validados
        $this->stats['no_show'] = Ticket::whereHas('schedule', function($query) use ($today) {
            $query->whereDate('departure_date', '<', $today);
        })
        ->where('status', 'paid')
        ->count();
    }

    public function refreshStats()
    {
        $this->loadStats();
        session()->flash('info', 'Estatísticas atualizadas.');
    }

    public function render()
    {
        return view('livewire.public.validate-ticket')->layout('layouts.passenger');
    }
}

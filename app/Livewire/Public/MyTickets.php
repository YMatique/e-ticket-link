<?php

namespace App\Livewire\Public;

use App\Models\Passenger;
use App\Models\Ticket;
use Livewire\Component;

class MyTickets extends Component
{
    // Campos de busca
    public $search_type = 'email'; // email, phone, ticket_number
    public $search_value = '';
    
    // Filtros
    public $status_filter = 'all'; // all, reserved, paid, validated, cancelled
    public $date_filter = 'all'; // all, today, week, month
    
    // Resultados
    public $tickets = [];
    public $searched = false;
    public $passenger = null;
    
    // Paginação
    public $perPage = 10;
    
    protected $queryString = [
        'search_type',
        'search_value',
        'status_filter',
        'date_filter',
    ];

    public function mount()
    {
        // Se vier da URL com parâmetros, fazer busca automática
        if ($this->search_value) {
            $this->searchTickets();
        }
    }

    public function updatedSearchType()
    {
        // Limpar campo ao mudar tipo de busca
        $this->search_value = '';
        $this->resetSearch();
    }

    public function updatedStatusFilter()
    {
        if ($this->searched) {
            $this->searchTickets();
        }
    }

    public function updatedDateFilter()
    {
        if ($this->searched) {
            $this->searchTickets();
        }
    }

    public function searchTickets()
    {
        $this->validate([
            'search_value' => 'required|string|min:3',
        ], [
            'search_value.required' => 'Por favor, digite algo para buscar',
            'search_value.min' => 'Digite pelo menos 3 caracteres',
        ]);

        $this->searched = true;
        $this->tickets = [];
        $this->passenger = null;

        // Buscar de acordo com o tipo
        if ($this->search_type === 'ticket_number') {
            $this->searchByTicketNumber();
        } elseif ($this->search_type === 'email') {
            $this->searchByEmail();
        } elseif ($this->search_type === 'phone') {
            $this->searchByPhone();
        }

        // Aplicar filtros
        $this->applyFilters();
    }

    private function searchByTicketNumber()
    {
        $ticket = Ticket::with([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus'
        ])
        ->where('ticket_number', 'LIKE', '%' . $this->search_value . '%')
        ->first();

        if ($ticket) {
            $this->tickets = collect([$ticket]);
            $this->passenger = $ticket->passenger;
        }
    }

    private function searchByEmail()
    {
        $passenger = Passenger::where('email', $this->search_value)->first();

        if ($passenger) {
            $this->passenger = $passenger;
            $this->tickets = Ticket::with([
                'schedule.route.originCity',
                'schedule.route.destinationCity',
                'schedule.bus'
            ])
            ->where('passenger_id', $passenger->id)
            ->orderBy('created_at', 'desc')
            ->get();
        }
    }

    private function searchByPhone()
    {
        // Limpar telefone (remover espaços, +, etc)
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->search_value);

        $passenger = Passenger::where('phone', 'LIKE', '%' . $cleanPhone . '%')->first();

        if ($passenger) {
            $this->passenger = $passenger;
            $this->tickets = Ticket::with([
                'schedule.route.originCity',
                'schedule.route.destinationCity',
                'schedule.bus'
            ])
            ->where('passenger_id', $passenger->id)
            ->orderBy('created_at', 'desc')
            ->get();
        }
    }

    private function applyFilters()
    {
        if ($this->tickets->isEmpty()) {
            return;
        }

        // Filtro por status
        if ($this->status_filter !== 'all') {
            $this->tickets = $this->tickets->filter(function ($ticket) {
                return $ticket->status === $this->status_filter;
            });
        }

        // Filtro por data
        if ($this->date_filter !== 'all') {
            $this->tickets = $this->tickets->filter(function ($ticket) {
                $scheduleDate = $ticket->schedule->departure_date;
                
                switch ($this->date_filter) {
                    case 'today':
                        return $scheduleDate->isToday();
                    case 'week':
                        return $scheduleDate->isCurrentWeek();
                    case 'month':
                        return $scheduleDate->isCurrentMonth();
                    case 'upcoming':
                        return $scheduleDate->isFuture();
                    case 'past':
                        return $scheduleDate->isPast();
                    default:
                        return true;
                }
            });
        }
    }

    public function resetSearch()
    {
        $this->tickets = [];
        $this->searched = false;
        $this->passenger = null;
        $this->status_filter = 'all';
        $this->date_filter = 'all';
    }

    public function downloadTicket($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        
        if (!$ticket) {
            session()->flash('error', 'Bilhete não encontrado.');
            return;
        }

        // TODO: Gerar PDF do bilhete
        session()->flash('info', 'Download de PDF em implementação. Por enquanto, imprima a página.');
        
        \Log::info('Download ticket solicitado', ['ticket_id' => $ticketId]);
    }

    public function resendTicket($ticketId)
    {
        $ticket = Ticket::with('passenger')->find($ticketId);
        
        if (!$ticket) {
            session()->flash('error', 'Bilhete não encontrado.');
            return;
        }

        // TODO: Reenviar email/SMS
        session()->flash('success', 'Bilhete reenviado para ' . $ticket->passenger->email);
        
        \Log::info('Ticket reenviado', [
            'ticket_id' => $ticketId,
            'email' => $ticket->passenger->email
        ]);
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'reserved' => 'bg-warning text-dark',
            'paid' => 'bg-success',
            'validated' => 'bg-primary',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabel($status)
    {
        return match($status) {
            'reserved' => 'Reservado',
            'paid' => 'Pago',
            'validated' => 'Validado',
            'cancelled' => 'Cancelado',
            default => 'Desconhecido',
        };
    }

    public function render()
    {
        return view('livewire.public.my-tickets')
         ->layout('layouts.passenger');;
    }
}

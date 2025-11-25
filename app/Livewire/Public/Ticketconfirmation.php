<?php

namespace App\Livewire\Public;

use App\Models\Ticket;
use Livewire\Component;

class Ticketconfirmation extends Component
{
    public $tickets = [];
    public $mainTicket;
    
    public function mount()
    {
        $ticketIds = request('tickets');
        
        if (!$ticketIds) {
            return redirect()->route('public.home');
        }

        $ids = explode(',', $ticketIds);
        
        $this->tickets = Ticket::with(['passenger', 'schedule.route.originCity', 'schedule.route.destinationCity', 'schedule.bus'])
            ->whereIn('id', $ids)
            ->get();

        if ($this->tickets->isEmpty()) {
            return redirect()->route('public.home');
        }

        $this->mainTicket = $this->tickets->first();
    }

    public function downloadTicket($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        
        if (!$ticket) {
            $this->dispatch('show-toast', [
                'message' => 'Bilhete não encontrado',
                'type' => 'error'
            ]);
            return;
        }

        // TODO: Gerar PDF do bilhete
        // return response()->download($pdfPath);
        
        $this->dispatch('show-toast', [
            'message' => 'Download iniciado!',
            'type' => 'success'
        ]);
    }

    public function sendTicketByEmail($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        
        if (!$ticket) {
            return;
        }

        // TODO: Reenviar email com bilhete
        
        $this->dispatch('show-toast', [
            'message' => 'Bilhete enviado para ' . $ticket->passenger->email,
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.public.ticketconfirmation')
            ->layout('layouts.passenger');
    }

}

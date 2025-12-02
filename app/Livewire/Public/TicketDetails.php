<?php

namespace App\Livewire\Public;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketDetails extends Component
{
    public Ticket $ticket;

    public $showCancelModal = false;

    public $cancelReason = '';

    public function mount(Ticket $ticket)
    {
        $account = Auth::guard('account')->user();

        // Verificar se o bilhete pertence a esta conta
        if (! $account || $ticket->account_id !== $account->id) {
            abort(403, 'Você não tem permissão para ver este bilhete.');
        }

        $this->ticket = $ticket;
        $this->ticket->load([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus',
            'account',
        ]);
    }

    public function downloadPdf()
    {
        // Redirecionar para rota de PDF
        return redirect()->route('tickets.pdf.download', $this->ticket);
    }

    public function openCancelModal()
    {
        if ($this->ticket->status === 'cancelled') {
            session()->flash('error', 'Este bilhete já foi cancelado.');

            return;
        }

        if ($this->ticket->status === 'validated') {
            session()->flash('error', 'Este bilhete já foi validado e não pode ser cancelado.');

            return;
        }

        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->cancelReason = '';
        $this->resetValidation();
    }

    public function cancelTicket()
    {
        $this->validate([
            'cancelReason' => 'required|string|min:10',
        ], [
            'cancelReason.required' => 'O motivo do cancelamento é obrigatório.',
            'cancelReason.min' => 'O motivo deve ter pelo menos 10 caracteres.',
        ]);

        // Atualizar status do bilhete
        $this->ticket->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $this->cancelReason,
        ]);

        $this->showCancelModal = false;

        session()->flash('success', 'Bilhete cancelado com sucesso!');

        // Recarregar dados
        $this->ticket->refresh();
    }

    public function resendEmail()
    {
        // TODO: Implementar reenvio de email
        // Mail::to($this->ticket->passenger->email)->send(new TicketPurchased($this->ticket));

        session()->flash('success', 'Email reenviado com sucesso!');
    }

    public function render()
    {
        $canCancel = in_array($this->ticket->status, ['reserved', 'paid'])
             && $this->ticket->schedule->departure_date->isFuture();

        return view('livewire.public.ticket-details', [
            'canCancel' => $canCancel,
        ])->layout('layouts.passenger');
    }
}

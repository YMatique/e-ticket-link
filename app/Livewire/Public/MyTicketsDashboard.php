<?php

namespace App\Livewire\Public;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyTicketsDashboard extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['statusFilter', 'search']);
        $this->resetPage();
    }

    public function render()
    {
         $account = Auth::guard('account')->user();

        if (!$account) {
            return redirect()->route('account.login');
        }

        // Buscar tickets
        $tickets = $account->tickets()
            ->with([
                'passenger',
                'schedule.route.originCity',
                'schedule.route.destinationCity',
                'schedule.bus'
            ])
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('ticket_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('passenger', function($pq) {
                          $pq->where('first_name', 'like', '%' . $this->search . '%')
                             ->orWhere('last_name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->latest()
            ->paginate(10);

        // Estatísticas
        $stats = [
            'total' => $account->getTotalTickets(),
            'active' => $account->getActiveTickets(),
            'passengers' => $account->passengers()->count(),
        ];
        return view('livewire.public.my-tickets-dashboard',[
            'tickets' => $tickets,
            'account' => $account,
            'stats' => $stats,
        ])->layout('layouts.passenger');;
    }
}

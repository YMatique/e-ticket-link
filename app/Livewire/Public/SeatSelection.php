<?php

namespace App\Livewire\Public;

use App\Models\Schedule;
use App\Models\TemporaryReservation;
use Livewire\Component;

class SeatSelection extends Component
{
    public $schedule;
    public $passengers = 1;
    
    // Assentos
    public $seatLayout = [];
    public $occupiedSeats = [];
    public $selectedSeats = [];
    public $temporaryReservedSeats = [];
    
    // Informações
    public $totalPrice = 0;
    public $reservationExpiry;

    public function mount(Schedule $schedule)
    {
        $this->schedule = $schedule->load(['route.originCity', 'route.destinationCity', 'bus']);
        $this->passengers = request('passengers', 1);

        // Verificar se horário está disponível
        if ($this->schedule->status !== 'active') {
            session()->flash('error', 'Este horário não está mais disponível.');
            return redirect()->route('public.trips', [
                'origin' => $this->schedule->route->origin_city_id,
                'destination' => $this->schedule->route->destination_city_id,
                'date' => $this->schedule->departure_date->format('Y-m-d'),
            ]);
        }

        // Carregar assentos ocupados
        $this->loadOccupiedSeats();
        
        // Gerar layout de assentos
        $this->generateSeatLayout();
        
        // Tempo de expiração para reserva (15 minutos)
        $this->reservationExpiry = now()->addMinutes(15)->timestamp;
    }

    public function loadOccupiedSeats()
    {
        // Assentos já vendidos/reservados
        $this->occupiedSeats = $this->schedule->tickets()
            ->whereIn('status', ['reserved', 'paid', 'validated'])
            ->pluck('seat_number')
            ->toArray();

        // Assentos temporariamente reservados (por outros usuários)
        $this->temporaryReservedSeats = TemporaryReservation::where('schedule_id', $this->schedule->id)
            ->where('expires_at', '>', now())
            ->where('session_id', '!=', session()->getId())
            ->pluck('seat_number')
            ->toArray();
    }

    public function generateSeatLayout()
    {
        $totalSeats = $this->schedule->bus->total_seats;
        $seatsPerRow = 4; // 2 à esquerda, corredor, 2 à direita
        $rows = ceil($totalSeats / $seatsPerRow);

        $seatNumber = 1;
        $layout = [];

        for ($row = 1; $row <= $rows; $row++) {
            $rowSeats = [];
            
            for ($col = 1; $col <= $seatsPerRow && $seatNumber <= $totalSeats; $col++) {
                $seatNum = (string)$seatNumber;
                $status = $this->getSeatStatus($seatNum);
                
                $rowSeats[] = [
                    'number' => $seatNum,
                    'position' => $col <= 2 ? 'left' : 'right',
                    'status' => $status,
                    'row' => $row,
                    'col' => $col,
                ];
                
                $seatNumber++;
            }
            
            $layout[] = $rowSeats;
        }

        $this->seatLayout = $layout;
    }

    public function getSeatStatus($seatNumber)
    {
        if (in_array($seatNumber, $this->occupiedSeats)) {
            return 'occupied';
        }
        
        if (in_array($seatNumber, $this->temporaryReservedSeats)) {
            return 'temporary';
        }
        
        if (in_array($seatNumber, $this->selectedSeats)) {
            return 'selected';
        }
        
        return 'available';
    }

    public function toggleSeat($seatNumber)
    {
        $status = $this->getSeatStatus($seatNumber);
        
        // Não permitir selecionar assentos ocupados ou temporariamente reservados
        if ($status === 'occupied' || $status === 'temporary') {
            $this->dispatch('show-toast', [
                'message' => 'Este assento não está disponível.',
                'type' => 'error'
            ]);
            return;
        }

        // Se já está selecionado, remover
        if (in_array($seatNumber, $this->selectedSeats)) {
            $this->selectedSeats = array_values(array_diff($this->selectedSeats, [$seatNumber]));
        } else {
            // Verificar limite de passageiros
            if (count($this->selectedSeats) >= $this->passengers) {
                $this->dispatch('show-toast', [
                    'message' => "Você só pode selecionar {$this->passengers} assento(s).",
                    'type' => 'warning'
                ]);
                return;
            }
            
            $this->selectedSeats[] = $seatNumber;
        }

        // Atualizar layout e preço
        $this->generateSeatLayout();
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalPrice = count($this->selectedSeats) * $this->schedule->price;
    }

    public function proceedToCheckout()
    {
        // Validar se selecionou assentos suficientes
        if (count($this->selectedSeats) !== $this->passengers) {
            $this->dispatch('show-toast', [
                'message' => "Por favor, selecione {$this->passengers} assento(s).",
                'type' => 'warning'
            ]);
            return;
        }

        // Recarregar assentos ocupados para garantir disponibilidade
        $this->loadOccupiedSeats();
        
        // Verificar se algum assento selecionado foi ocupado
        $unavailableSeats = array_intersect(
            $this->selectedSeats, 
            array_merge($this->occupiedSeats, $this->temporaryReservedSeats)
        );

        if (!empty($unavailableSeats)) {
            $this->dispatch('show-toast', [
                'message' => 'Alguns assentos selecionados não estão mais disponíveis. Por favor, selecione outros.',
                'type' => 'error'
            ]);
            
            // Remover assentos indisponíveis da seleção
            $this->selectedSeats = array_values(array_diff($this->selectedSeats, $unavailableSeats));
            $this->generateSeatLayout();
            return;
        }

        // Criar reservas temporárias
        foreach ($this->selectedSeats as $seatNumber) {
            TemporaryReservation::updateOrCreate(
                [
                    'schedule_id' => $this->schedule->id,
                    'seat_number' => $seatNumber,
                    'session_id' => session()->getId(),
                ],
                [
                    'expires_at' => now()->addMinutes(15),
                ]
            );
        }

        // Redirecionar para checkout
        return redirect()->route('public.checkout', [
            'schedule' => $this->schedule->id,
            'seats' => implode(',', $this->selectedSeats),
        ]);
    }

    public function render()
    {
        return view('livewire.public.seat-selection')
            ->layout('layouts.passenger');
    }
    // public function render()
    // {
    //     return view('livewire.public.seat-selection');
    // }
}

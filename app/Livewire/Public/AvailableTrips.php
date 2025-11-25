<?php

namespace App\Livewire\Public;

use App\Models\City;
use App\Models\Schedule;
use Livewire\Component;

class AvailableTrips extends Component
{
     // Parâmetros de busca
    public $origin_city_id;
    public $destination_city_id;
    public $travel_date;
    public $passengers = 1;

    // Dados
    public $schedules = [];
    public $originCity;
    public $destinationCity;

    // Filtros adicionais
    public $sortBy = 'price'; // price, time
    public $filterByTime = 'all'; // all, morning, afternoon, evening

    public function mount()
    {
        // Receber parâmetros da URL
        $this->origin_city_id = request('origin');
        $this->destination_city_id = request('destination');
        $this->travel_date = request('date', now()->format('Y-m-d'));
        $this->passengers = request('passengers', 1);

        // Validar parâmetros
        if (!$this->origin_city_id || !$this->destination_city_id) {
            return redirect()->route('public.home')
                ->with('error', 'Por favor, selecione origem e destino.');
        }

        // Carregar cidades
        $this->originCity = City::with('province')->find($this->origin_city_id);
        $this->destinationCity = City::with('province')->find($this->destination_city_id);

        // Buscar horários
        $this->searchSchedules();
    }

    public function searchSchedules()
    {
        $query = Schedule::with(['route.originCity', 'route.destinationCity', 'bus'])
            ->whereHas('route', function($q) {
                $q->where('origin_city_id', $this->origin_city_id)
                  ->where('destination_city_id', $this->destination_city_id);
            })
            ->where('departure_date', $this->travel_date)
            ->where('status', 'active');

        // Filtro por período do dia
        if ($this->filterByTime !== 'all') {
            $query->where(function($q) {
                switch($this->filterByTime) {
                    case 'morning':
                        $q->whereTime('departure_time', '>=', '06:00:00')
                          ->whereTime('departure_time', '<', '12:00:00');
                        break;
                    case 'afternoon':
                        $q->whereTime('departure_time', '>=', '12:00:00')
                          ->whereTime('departure_time', '<', '18:00:00');
                        break;
                    case 'evening':
                        $q->whereTime('departure_time', '>=', '18:00:00')
                          ->whereTime('departure_time', '<', '23:59:59');
                        break;
                }
            });
        }

        // Ordenação
        switch($this->sortBy) {
            case 'price':
                $query->orderBy('price', 'asc');
                break;
            case 'time':
                $query->orderBy('departure_time', 'asc');
                break;
        }

        $this->schedules = $query->get()->map(function($schedule) {
            $availableSeats = $schedule->bus->total_seats - $schedule->tickets()
                ->whereIn('status', ['reserved', 'paid', 'validated'])
                ->count();

            return [
                'id' => $schedule->id,
                'departure_time' => $schedule->departure_time,
                'departure_date' => $schedule->departure_date->format('d/m/Y'),
                'price' => $schedule->price,
                'bus_model' => $schedule->bus->model,
                'bus_registration' => $schedule->bus->registration_number,
                'total_seats' => $schedule->bus->total_seats,
                'available_seats' => $availableSeats,
                'has_availability' => $availableSeats >= $this->passengers,
                'distance' => $schedule->route->distance,
                'estimated_duration' => $schedule->route->estimated_duration,
            ];
        });
    }

    public function updatedSortBy()
    {
        $this->searchSchedules();
    }

    public function updatedFilterByTime()
    {
        $this->searchSchedules();
    }

    public function selectSchedule($scheduleId)
    {
        return redirect()->route('public.seats', [
            'schedule' => $scheduleId,
            'passengers' => $this->passengers
        ]);
    }

    public function render()
    {
        return view('livewire.public.available-trips')
            ->layout('layouts.passenger');
    }
    // public function render()
    // {
    //     return view('livewire.public.available-trips');
    // }
}

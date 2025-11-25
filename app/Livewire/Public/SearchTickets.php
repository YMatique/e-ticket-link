<?php

namespace App\Livewire\Public;

use App\Models\City;
use App\Models\Schedule;
use Livewire\Component;

class SearchTickets extends Component
{
     // Propriedades do formulário
    public $origin_city_id;
    public $destination_city_id;
    public $travel_date;
    public $passengers = 1;

    // Dados
    public $cities = [];
    public $popularRoutes = [];

    // Validação em tempo real
    protected $rules = [
        'origin_city_id' => 'required|exists:cities,id|different:destination_city_id',
        'destination_city_id' => 'required|exists:cities,id|different:origin_city_id',
        'travel_date' => 'required|date|after_or_equal:today',
        'passengers' => 'required|integer|min:1|max:10',
    ];

    protected $messages = [
        'origin_city_id.required' => 'Selecione a cidade de origem',
        'origin_city_id.different' => 'Origem e destino devem ser diferentes',
        'destination_city_id.required' => 'Selecione a cidade de destino',
        'destination_city_id.different' => 'Origem e destino devem ser diferentes',
        'travel_date.required' => 'Selecione a data da viagem',
        'travel_date.after_or_equal' => 'A data deve ser hoje ou posterior',
        'passengers.required' => 'Informe o número de passageiros',
        'passengers.min' => 'Mínimo de 1 passageiro',
        'passengers.max' => 'Máximo de 10 passageiros por compra',
    ];

    public function mount()
    {
        // Carregar cidades
        $this->cities = City::with('province')
            ->orderBy('name')
            ->get();

        // Data padrão: hoje
        $this->travel_date = now()->format('Y-m-d');

        // Carregar rotas populares
        $this->loadPopularRoutes();
    }

    public function loadPopularRoutes()
    {
        // Buscar as rotas mais vendidas
        $this->popularRoutes = Schedule::with(['route.originCity', 'route.destinationCity'])
            ->where('departure_date', '>=', now())
            ->where('status', 'active')
            ->take(6)
            ->get()
            ->groupBy('route_id')
            ->map(fn($schedules) => $schedules->first())
            ->values();
    }

    public function swapCities()
    {
        $temp = $this->origin_city_id;
        $this->origin_city_id = $this->destination_city_id;
        $this->destination_city_id = $temp;
    }

    public function searchTrips()
    {
        $this->validate();

        // Redirecionar para página de resultados com parâmetros
        return redirect()->route('public.trips', [
            'origin' => $this->origin_city_id,
            'destination' => $this->destination_city_id,
            'date' => $this->travel_date,
            'passengers' => $this->passengers,
        ]);
    }

    public function selectRoute($originId, $destinationId)
    {
        $this->origin_city_id = $originId;
        $this->destination_city_id = $destinationId;
    }

    public function render()
    {
        return view('livewire.public.search-tickets')
            ->layout('layouts.passenger');
    }
    // public function render()
    // {
    //     return view('livewire.public.search-tickets');
    // }
}

<div>
    <!-- Header com informações da busca -->
    <section class="bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">
                        <i class="ph-map-pin-line me-2"></i>
                        {{ $originCity->name }}
                        <i class="ph-arrow-right mx-2"></i>
                        {{ $destinationCity->name }}
                    </h4>
                    <div class="d-flex gap-3 text-white-50">
                        <span>
                            <i class="ph-calendar me-1"></i>
                            {{ Carbon\Carbon::parse($travel_date)->format('d/m/Y') }}
                        </span>
                        <span>
                            <i class="ph-users me-1"></i>
                            {{ $passengers }} {{ $passengers == 1 ? 'Passageiro' : 'Passageiros' }}
                        </span>
                        <span>
                            <i class="ph-map-trifold me-1"></i>
                            {{ number_format($originCity->province->name == $destinationCity->province->name ? 50 : 200, 0) }} km
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('public.home') }}" class="btn btn-light">
                        <i class="ph-magnifying-glass me-2"></i>
                        Nova Busca
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Filtros Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ph-funnel me-2"></i>
                            Filtros
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Ordenar por -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Ordenar por:</label>
                            <select wire:model.live="sortBy" class="form-select form-select-sm">
                                <option value="price">Menor Preço</option>
                                <option value="time">Horário</option>
                            </select>
                        </div>

                        <!-- Período do dia -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Período:</label>
                            <div class="d-grid gap-2">
                                <button type="button" 
                                        wire:click="$set('filterByTime', 'all')"
                                        class="btn btn-sm {{ $filterByTime == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Todo o dia
                                </button>
                                <button type="button" 
                                        wire:click="$set('filterByTime', 'morning')"
                                        class="btn btn-sm {{ $filterByTime == 'morning' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="ph-sun me-1"></i>
                                    Manhã (06h-12h)
                                </button>
                                <button type="button" 
                                        wire:click="$set('filterByTime', 'afternoon')"
                                        class="btn btn-sm {{ $filterByTime == 'afternoon' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="ph-sun-horizon me-1"></i>
                                    Tarde (12h-18h)
                                </button>
                                <button type="button" 
                                        wire:click="$set('filterByTime', 'evening')"
                                        class="btn btn-sm {{ $filterByTime == 'evening' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="ph-moon me-1"></i>
                                    Noite (18h-00h)
                                </button>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="alert alert-info mb-0">
                            <div class="d-flex">
                                <i class="ph-info ph-lg me-2"></i>
                                <div class="small">
                                    <strong>Dica:</strong> Reserve com antecedência para garantir os melhores lugares!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Viagens -->
            <div class="col-lg-9">
                <!-- Cabeçalho dos resultados -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        {{ count($schedules) }} {{ count($schedules) == 1 ? 'Viagem Disponível' : 'Viagens Disponíveis' }}
                    </h5>
                </div>

                <!-- Lista -->
                <div class="d-flex flex-column gap-3">
                    @forelse($schedules as $schedule)
                        <div class="card border-0 shadow-sm hover-shadow {{ !$schedule['has_availability'] ? 'opacity-50' : '' }}">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <!-- Horário -->
                                    <div class="col-lg-3 mb-3 mb-lg-0">
                                        <div class="text-center">
                                            <div class="display-6 fw-bold text-primary mb-1">
                                                {{ substr($schedule['departure_time'], 0, 5) }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="ph-calendar-blank me-1"></i>
                                                {{ $schedule['departure_date'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informações do Autocarro -->
                                    <div class="col-lg-4 mb-3 mb-lg-0">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="ph-bus ph-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $schedule['bus_model'] }}</h6>
                                                <div class="small text-muted mb-2">
                                                    {{ $schedule['bus_registration'] }}
                                                </div>
                                                <div class="d-flex gap-3 small">
                                                    <span class="text-success">
                                                        <i class="ph-armchair me-1"></i>
                                                        {{ $schedule['available_seats'] }} disponíveis
                                                    </span>
                                                    <span class="text-muted">
                                                        <i class="ph-clock me-1"></i>
                                                        ~{{ $schedule['estimated_duration'] }}h
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preço e Ação -->
                                    <div class="col-lg-5">
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                <div class="text-muted small mb-1">A partir de</div>
                                                <div class="h4 mb-0 fw-bold text-primary">
                                                    {{ number_format($schedule['price'], 2) }} MT
                                                </div>
                                                <div class="small text-muted">por pessoa</div>
                                            </div>
                                            <div class="col-6">
                                                @if($schedule['has_availability'])
                                                    <button wire:click="selectSchedule({{ $schedule['id'] }})" 
                                                            class="btn btn-primary w-100">
                                                        <i class="ph-arrow-right me-2"></i>
                                                        Selecionar
                                                    </button>
                                                @else
                                                    <button class="btn btn-secondary w-100" disabled>
                                                        <i class="ph-x-circle me-2"></i>
                                                        Esgotado
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Avisos -->
                                @if($schedule['available_seats'] > 0 && $schedule['available_seats'] <= 5)
                                    <div class="alert alert-warning mb-0 mt-3">
                                        <i class="ph-warning me-2"></i>
                                        <strong>Últimos {{ $schedule['available_seats'] }} lugares disponíveis!</strong>
                                        Reserve agora antes que esgote.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <!-- Nenhuma viagem encontrada -->
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ph-magnifying-glass ph-4x text-muted mb-3"></i>
                                <h4 class="mb-3">Nenhuma viagem encontrada</h4>
                                <p class="text-muted mb-4">
                                    Não há viagens disponíveis para esta rota na data selecionada.
                                </p>
                                <div class="d-flex gap-2 justify-content-center flex-wrap">
                                    <a href="{{ route('public.home') }}" class="btn btn-primary">
                                        <i class="ph-magnifying-glass me-2"></i>
                                        Fazer Nova Busca
                                    </a>
                                    <button wire:click="$set('travel_date', '{{ now()->addDay()->format('Y-m-d') }}')" 
                                            class="btn btn-outline-primary">
                                        <i class="ph-calendar-plus me-2"></i>
                                        Buscar Amanhã
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Dicas -->
                @if(count($schedules) > 0)
                    <div class="card bg-light border-0 mt-4">
                        <div class="card-body">
                            <h6 class="mb-3">
                                <i class="ph-lightbulb me-2"></i>
                                Dicas para sua viagem
                            </h6>
                            <ul class="mb-0 small">
                                <li>Chegue pelo menos 30 minutos antes da partida</li>
                                <li>Traga seu documento de identificação</li>
                                <li>Bagagem de mão: máximo 10kg</li>
                                <li>Crianças abaixo de 5 anos não pagam (no colo)</li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
</style>
@endpush
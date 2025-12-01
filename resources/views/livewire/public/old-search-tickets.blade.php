<div>
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-3">
                        Viaje com Conforto e Segurança
                    </h1>
                    <p class="lead mb-4">
                        Compre seu bilhete de autocarro online de forma rápida e segura. 
                        Escolha seu assento e viaje com tranquilidade!
                    </p>
                    <div class="d-flex gap-2">
                        <div class="d-flex align-items-center bg-white bg-opacity-10 rounded px-3 py-2">
                            <i class="ph-check-circle ph-lg me-2"></i>
                            <span>Pagamento Seguro</span>
                        </div>
                        <div class="d-flex align-items-center bg-white bg-opacity-10 rounded px-3 py-2">
                            <i class="ph-device-mobile ph-lg me-2"></i>
                            <span>Bilhete Digital</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Search Form Card -->
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4">
                            <h4 class="text-dark mb-4">
                                <i class="ph-magnifying-glass me-2"></i>
                                Buscar Viagem
                            </h4>

                            <form wire:submit.prevent="searchTrips">
                                <!-- Origem -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">
                                        <i class="ph-map-pin-line me-1"></i>
                                        Origem
                                    </label>
                                    <select wire:model.live="origin_city_id" class="form-select @error('origin_city_id') is-invalid @enderror">
                                        <option value="">Selecione a cidade de partida</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">
                                                {{ $city->name }} - {{ $city->province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('origin_city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Botão Trocar -->
                                <div class="text-center mb-3">
                                    <button type="button" wire:click="swapCities" class="btn btn-light btn-sm rounded-circle" 
                                            style="width: 40px; height: 40px;">
                                        <i class="ph-swap"></i>
                                    </button>
                                </div>

                                <!-- Destino -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">
                                        <i class="ph-map-pin me-1"></i>
                                        Destino
                                    </label>
                                    <select wire:model.live="destination_city_id" class="form-select @error('destination_city_id') is-invalid @enderror">
                                        <option value="">Selecione a cidade de destino</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">
                                                {{ $city->name }} - {{ $city->province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('destination_city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Data -->
                                    <div class="col-md-7 mb-3">
                                        <label class="form-label fw-semibold text-dark">
                                            <i class="ph-calendar-blank me-1"></i>
                                            Data da Viagem
                                        </label>
                                        <input type="date" wire:model.live="travel_date" 
                                               class="form-control @error('travel_date') is-invalid @enderror"
                                               min="{{ now()->format('Y-m-d') }}">
                                        @error('travel_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Passageiros -->
                                    <div class="col-md-5 mb-3">
                                        <label class="form-label fw-semibold text-dark">
                                            <i class="ph-users me-1"></i>
                                            Passageiros
                                        </label>
                                        <input type="number" wire:model.live="passengers" 
                                               class="form-control @error('passengers') is-invalid @enderror"
                                               min="1" max="10">
                                        @error('passengers')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3">
                                    <i class="ph-magnifying-glass me-2"></i>
                                    Buscar Viagens Disponíveis
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rotas Populares -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Rotas Populares</h2>
                <p class="text-muted">Escolha uma das nossas rotas mais procuradas</p>
            </div>

            <div class="row g-4">
                @forelse($popularRoutes as $schedule)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 hover-shadow" style="cursor: pointer;"
                             wire:click="selectRoute({{ $schedule->route->origin_city_id }}, {{ $schedule->route->destination_city_id }})">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-fill">
                                        <h5 class="mb-0">{{ $schedule->route->originCity->name }}</h5>
                                        <small class="text-muted">{{ $schedule->route->originCity->province->name }}</small>
                                    </div>
                                    <div class="px-3">
                                        <i class="ph-arrow-right text-primary ph-lg"></i>
                                    </div>
                                    <div class="flex-fill text-end">
                                        <h5 class="mb-0">{{ $schedule->route->destinationCity->name }}</h5>
                                        <small class="text-muted">{{ $schedule->route->destinationCity->province->name }}</small>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="ph-map-trifold me-1"></i>
                                        {{ number_format($schedule->route->distance, 0) }} km
                                    </div>
                                    <div class="fw-bold text-primary">
                                        A partir de {{ number_format($schedule->price, 2) }} MT
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="ph-airplane-tilt ph-4x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma rota disponível no momento</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Como Funciona -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Como Funciona</h2>
                <p class="text-muted">Compre seu bilhete em 4 passos simples</p>
            </div>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 80px; height: 80px;">
                            <i class="ph-magnifying-glass ph-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-2">1. Busque</h5>
                        <p class="text-muted">Escolha origem, destino e data da viagem</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 80px; height: 80px;">
                            <i class="ph-armchair ph-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-2">2. Escolha</h5>
                        <p class="text-muted">Selecione o horário e seu assento preferido</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 80px; height: 80px;">
                            <i class="ph-credit-card ph-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">3. Pague</h5>
                        <p class="text-muted">Pagamento seguro via M-Pesa ou e-Mola</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 80px; height: 80px;">
                            <i class="ph-qr-code ph-2x text-info"></i>
                        </div>
                        <h5 class="fw-bold mb-2">4. Viaje</h5>
                        <p class="text-muted">Receba seu bilhete com QR Code por SMS/Email</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vantagens -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="ph-shield-check ph-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <h5 class="fw-bold mb-2">Segurança</h5>
                            <p class="text-muted mb-0">Pagamentos 100% seguros e protegidos</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="ph-clock ph-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <h5 class="fw-bold mb-2">Rapidez</h5>
                            <p class="text-muted mb-0">Compre em menos de 3 minutos</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="ph-headset ph-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <h5 class="fw-bold mb-2">Suporte 24/7</h5>
                            <p class="text-muted mb-0">Estamos sempre prontos para ajudar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-5px);
    }
</style>
@endpush
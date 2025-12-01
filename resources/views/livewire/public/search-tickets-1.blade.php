@extends('layouts.passenger')

@section('title', 'e-TicketLink - Compre seus bilhetes online')

@push('styles')
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --accent-color: #ff6b35;
        --dark-bg: #0a0f1e;
        --light-bg: #f8f9fa;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--dark-bg) 0%, #1a2435 100%);
        padding: 4rem 0 6rem;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="800" cy="200" r="300" fill="rgba(255,107,53,0.05)"/><circle cx="900" cy="600" r="400" fill="rgba(13,110,253,0.05)"/></svg>') no-repeat center;
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: white;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .hero-title span {
        color: var(--accent-color);
        display: block;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 2.5rem;
        line-height: 1.7;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 107, 53, 0.15);
        color: var(--accent-color);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 107, 53, 0.3);
    }

    /* Search Card */
    .search-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        padding: 2.5rem;
        margin-top: -4rem;
        position: relative;
        z-index: 3;
    }

    .search-card-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .search-card-header h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
    }

    .search-card-header p {
        color: var(--secondary-color);
        font-size: 1rem;
    }

    .form-floating > label {
        color: var(--secondary-color);
        font-weight: 500;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.15);
    }

    .btn-search {
        background: var(--accent-color);
        border: none;
        padding: 1rem 3rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-search:hover {
        background: #ff8555;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
    }

    .btn-swap {
        background: var(--light-bg);
        border: 2px solid #dee2e6;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-swap:hover {
        background: var(--accent-color);
        border-color: var(--accent-color);
        color: white;
        transform: rotate(180deg);
    }

    /* Features Section */
    .features-section {
        padding: 5rem 0;
        background: white;
    }

    .section-header {
        text-align: center;
        max-width: 700px;
        margin: 0 auto 4rem;
    }

    .section-label {
        color: var(--accent-color);
        font-size: 0.875rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark-bg);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .section-subtitle {
        font-size: 1.125rem;
        color: var(--secondary-color);
        line-height: 1.7;
    }

    .feature-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 2rem;
        height: 100%;
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        border-color: var(--accent-color);
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 107, 53, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
        background: var(--accent-color);
        transform: scale(1.1);
    }

    .feature-card:hover .feature-icon i {
        color: white;
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.75rem;
    }

    .feature-text {
        color: var(--secondary-color);
        line-height: 1.6;
        font-size: 0.975rem;
    }

    /* Steps Section */
    .steps-section {
        padding: 5rem 0;
        background: var(--light-bg);
    }

    .step-card {
        text-align: center;
        padding: 2rem 1rem;
    }

    .step-number {
        width: 80px;
        height: 80px;
        background: white;
        border: 3px solid var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent-color);
    }

    .step-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .step-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.75rem;
    }

    .step-text {
        color: var(--secondary-color);
        font-size: 0.975rem;
    }

    /* Stats Section */
    .stats-section {
        background: var(--dark-bg);
        padding: 4rem 0;
        color: white;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: var(--accent-color);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    /* CTA Section */
    .cta-section {
        padding: 5rem 0;
        background: white;
    }

    .cta-card {
        background: var(--dark-bg);
        border-radius: 24px;
        padding: 4rem 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--accent-color);
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 2.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Popular Routes */
    .routes-section {
        padding: 5rem 0;
        background: white;
    }

    .route-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
    }

    .route-card:hover {
        border-color: var(--accent-color);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px);
    }

    .route-cities {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .route-city {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-bg);
    }

    .route-arrow {
        color: var(--accent-color);
        font-size: 1.5rem;
    }

    .route-info {
        font-size: 0.875rem;
        color: var(--secondary-color);
    }

    .route-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--accent-color);
        margin-top: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .search-card {
            padding: 1.5rem;
            margin-top: -2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .cta-title {
            font-size: 2rem;
        }

        .stat-number {
            font-size: 2.5rem;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content animate-fade-in">
                <span class="hero-badge">
                    <i class="ph-check-circle me-1"></i>
                    PLATAFORMA Nº1 EM MOÇAMBIQUE
                </span>
                <h1 class="hero-title">
                    Viaje com
                    <span>Conforto e Segurança</span>
                </h1>
                <p class="hero-subtitle">
                    Reserve seus bilhetes de autocarro online de forma rápida, segura e sem complicações. 
                    A melhor experiência de viagem começa aqui.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ph-check-circle text-success fs-4"></i>
                        <span class="text-white">Pagamento Seguro</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="ph-check-circle text-success fs-4"></i>
                        <span class="text-white">Suporte 24/7</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="ph-check-circle text-success fs-4"></i>
                        <span class="text-white">Bilhete Digital</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Card Section -->
<section class="container">
    <div class="search-card animate-fade-in">
        <div class="search-card-header">
            <h3>Encontre sua viagem ideal</h3>
            <p>Preencha os dados abaixo e descubra as melhores opções para você</p>
        </div>

        <form wire:submit.prevent="searchTrips">
            <div class="row g-3 align-items-end">
                <!-- Origem -->
                <div class="col-lg-3 col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="origin" wire:model="origin_city_id" required>
                            <option value="">Selecione...</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $city->name }} - {{ $city->province->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="origin">
                            <i class="ph-map-pin me-2"></i>Origem
                        </label>
                    </div>
                    @error('origin_city_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Swap Button -->
                <div class="col-lg-auto col-md-12 text-center">
                    <button type="button" class="btn-swap" wire:click="swapCities" title="Trocar origem e destino">
                        <i class="ph-swap"></i>
                    </button>
                </div>

                <!-- Destino -->
                <div class="col-lg-3 col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="destination" wire:model="destination_city_id" required>
                            <option value="">Selecione...</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $city->name }} - {{ $city->province->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="destination">
                            <i class="ph-map-pin me-2"></i>Destino
                        </label>
                    </div>
                    @error('destination_city_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Data -->
                <div class="col-lg-2 col-md-6">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="date" wire:model="travel_date" required>
                        <label for="date">
                            <i class="ph-calendar me-2"></i>Data
                        </label>
                    </div>
                    @error('travel_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Passageiros -->
                <div class="col-lg-2 col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="passengers" wire:model="passengers" required>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Pessoa' : 'Pessoas' }}</option>
                            @endfor
                        </select>
                        <label for="passengers">
                            <i class="ph-users me-2"></i>Passageiros
                        </label>
                    </div>
                </div>

                <!-- Botão Buscar -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-search">
                        <i class="ph-magnifying-glass me-2"></i>
                        Buscar Viagens Disponíveis
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Steps Section -->
<section class="steps-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">COMO FUNCIONA</div>
            <h2 class="section-title">Reserve em 4 passos simples</h2>
            <p class="section-subtitle">O processo é rápido, fácil e 100% seguro</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon-wrap bg-primary bg-opacity-10">
                        <i class="ph-magnifying-glass ph-2x text-primary"></i>
                    </div>
                    <h4 class="step-title">1. Busque</h4>
                    <p class="step-text">Escolha origem, destino e data da viagem</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon-wrap bg-success bg-opacity-10">
                        <i class="ph-armchair ph-2x text-success"></i>
                    </div>
                    <h4 class="step-title">2. Escolha</h4>
                    <p class="step-text">Selecione o horário e seu assento preferido</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon-wrap bg-warning bg-opacity-10">
                        <i class="ph-credit-card ph-2x text-warning"></i>
                    </div>
                    <h4 class="step-title">3. Pague</h4>
                    <p class="step-text">Pagamento seguro via M-Pesa ou e-Mola</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon-wrap bg-info bg-opacity-10">
                        <i class="ph-qr-code ph-2x text-info"></i>
                    </div>
                    <h4 class="step-title">4. Viaje</h4>
                    <p class="step-text">Apresente seu bilhete digital no embarque</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">VANTAGENS</div>
            <h2 class="section-title">Por que escolher o e-TicketLink?</h2>
            <p class="section-subtitle">A forma mais moderna e conveniente de viajar por Moçambique</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ph-lock-key text-primary"></i>
                    </div>
                    <h4 class="feature-title">Pagamento Seguro</h4>
                    <p class="feature-text">Transações protegidas com criptografia de ponta. Pague via M-Pesa, e-Mola ou cartão com total segurança.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ph-clock text-primary"></i>
                    </div>
                    <h4 class="feature-title">Disponível 24/7</h4>
                    <p class="feature-text">Compre seus bilhetes a qualquer hora, de qualquer lugar. Sem filas, sem esperas, com total praticidade.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ph-device-mobile text-primary"></i>
                    </div>
                    <h4 class="feature-title">Bilhete Digital</h4>
                    <p class="feature-text">Receba seu bilhete instantaneamente no email e celular. Apresente o QR Code e embarque sem complicações.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ph-map-pin text-primary"></i>
                    </div>
                    <h4 class="feature-title">Escolha seu Assento</h4>
                    <p class="feature-text">Visualize o mapa do autocarro e escolha exatamente onde você quer sentar. Conforto garantido!</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ph-receipt text-primary"></i>
                    </div>
                    <h4 class="feature-title">Histórico Completo</h4>
                    <p class="feature-text">Acesse todos os seus bilhetes anteriores e acompanhe suas viagens em um só lugar.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ph-headset text-primary"></i>
                    </div>
                    <h4 class="feature-title">Suporte Dedicado</h4>
                    <p class="feature-text">Nossa equipe está sempre disponível para ajudar você antes, durante e depois da sua viagem.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Bilhetes Vendidos</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Rotas Disponíveis</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfação dos Clientes</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Suporte Disponível</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <h2 class="cta-title">Pronto para sua próxima viagem?</h2>
            <p class="cta-subtitle">
                Junte-se a milhares de passageiros que já viajam com conforto e segurança. 
                Reserve agora e aproveite as melhores rotas de Moçambique.
            </p>
            <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" class="btn btn-primary btn-lg px-5">
                <i class="ph-ticket me-2"></i>
                Comprar Meu Bilhete Agora
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Scroll suave para o topo ao clicar no CTA
    document.addEventListener('DOMContentLoaded', function() {
        // Animação de fade-in nos elementos ao scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .step-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    });
</script>
@endpush
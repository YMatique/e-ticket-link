<div>
    <!-- Hero Section -->
    <div class="bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="ph-ticket ph-2x me-2"></i>
                        Meus Bilhetes
                    </h1>
                    <p class="lead mb-0">
                        Consulte seus bilhetes por email, telefone ou número do bilhete
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="container py-5">
        <!-- Alertas -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <i class="ph-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <i class="ph-warning-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('info'))
            <div class="alert alert-info alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <i class="ph-info me-2"></i>
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="ph-magnifying-glass me-2"></i>
                            Buscar Bilhetes
                        </h5>

                        <form wire:submit.prevent="searchTickets">
                            <!-- Tipo de Busca -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Buscar por:</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="search_type" id="type_email" 
                                           wire:model.live="search_type" value="email">
                                    <label class="btn btn-outline-primary" for="type_email">
                                        <i class="ph-envelope me-1"></i>
                                        Email
                                    </label>

                                    <input type="radio" class="btn-check" name="search_type" id="type_phone" 
                                           wire:model.live="search_type" value="phone">
                                    <label class="btn btn-outline-primary" for="type_phone">
                                        <i class="ph-phone me-1"></i>
                                        Telefone
                                    </label>

                                    <input type="radio" class="btn-check" name="search_type" id="type_ticket" 
                                           wire:model.live="search_type" value="ticket_number">
                                    <label class="btn btn-outline-primary" for="type_ticket">
                                        <i class="ph-ticket me-1"></i>
                                        Nº Bilhete
                                    </label>
                                </div>
                            </div>

                            <!-- Campo de Busca -->
                            <div class="mb-4">
                                @if($search_type === 'email')
                                    <input type="email" class="form-control form-control-lg" 
                                           wire:model="search_value"
                                           placeholder="Digite seu email (ex: joao@example.com)">
                                @elseif($search_type === 'phone')
                                    <input type="text" class="form-control form-control-lg" 
                                           wire:model="search_value"
                                           placeholder="Digite seu telefone (ex: 84 123 4567)">
                                @else
                                    <input type="text" class="form-control form-control-lg" 
                                           wire:model="search_value"
                                           placeholder="Digite o número do bilhete (ex: TKT-20251125-ABC123)">
                                @endif
                            </div>

                            <!-- Botões -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1" 
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="searchTickets">
                                        <i class="ph-magnifying-glass me-2"></i>
                                        Buscar Bilhetes
                                    </span>
                                    <span wire:loading wire:target="searchTickets">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Buscando...
                                    </span>
                                </button>

                                @if($searched)
                                    <button type="button" class="btn btn-outline-secondary btn-lg" 
                                            wire:click="resetSearch">
                                        <i class="ph-x"></i>
                                        Limpar
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        @if($searched)
            @if($tickets->count() > 0)
                <!-- Info do Passageiro -->
                @if($passenger)
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card shadow-sm bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px; font-size: 24px;">
                                                <i class="ph-user"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">{{ $passenger->first_name }} {{ $passenger->last_name }}</h5>
                                            <div class="text-muted">
                                                <i class="ph-envelope me-1"></i> {{ $passenger->email }}
                                                @if($passenger->phone)
                                                    <span class="ms-3">
                                                        <i class="ph-phone me-1"></i> {{ $passenger->phone }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-primary fs-5">
                                                {{ $tickets->count() }} bilhete(s)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label fw-semibold">
                            <i class="ph-funnel me-1"></i>
                            Filtrar por Status:
                        </label>
                        <select class="form-select" wire:model.live="status_filter">
                            <option value="all">Todos os Status</option>
                            <option value="reserved">Reservados</option>
                            <option value="paid">Pagos</option>
                            <option value="validated">Validados</option>
                            <option value="cancelled">Cancelados</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="ph-calendar me-1"></i>
                            Filtrar por Data:
                        </label>
                        <select class="form-select" wire:model.live="date_filter">
                            <option value="all">Todas as Datas</option>
                            <option value="today">Hoje</option>
                            <option value="week">Esta Semana</option>
                            <option value="month">Este Mês</option>
                            <option value="upcoming">Próximas Viagens</option>
                            <option value="past">Viagens Passadas</option>
                        </select>
                    </div>
                </div>

                <!-- Lista de Bilhetes -->
                <div class="row">
                    @foreach($tickets as $ticket)
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-sm h-100 ticket-card">
                                <div class="card-header bg-white border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $ticket->ticket_number }}</h6>
                                            <small class="text-muted">
                                                Comprado em {{ $ticket->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <span class="badge {{ $this->getStatusBadgeClass($ticket->status) }}">
                                            {{ $this->getStatusLabel($ticket->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Rota -->
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-center">
                                                <div class="fw-bold fs-5">{{ $ticket->schedule->route->originCity->name }}</div>
                                                <small class="text-muted">{{ $ticket->schedule->departure_time }}</small>
                                            </div>
                                            <div class="px-3">
                                                <i class="ph-arrow-right text-primary" style="font-size: 24px;"></i>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold fs-5">{{ $ticket->schedule->route->destinationCity->name }}</div>
                                                <small class="text-muted">{{ $ticket->schedule->arrival_time }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detalhes -->
                                    <div class="border-top pt-3">
                                        <div class="row g-3 text-center">
                                            <div class="col-4">
                                                <div class="text-muted small">Data</div>
                                                <div class="fw-semibold">
                                                    {{ $ticket->schedule->departure_date->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-muted small">Assento</div>
                                                <div class="fw-semibold">{{ $ticket->seat_number }}</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-muted small">Autocarro</div>
                                                <div class="fw-semibold">{{ $ticket->schedule->bus->plate_number }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preço -->
                                    <div class="border-top mt-3 pt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Preço:</span>
                                            <span class="fs-4 fw-bold text-primary">
                                                {{ number_format($ticket->price, 2) }} MT
                                            </span>
                                        </div>
                                    </div>

                                    <!-- QR Code -->
                                    @if($ticket->qr_code)
                                        <div class="text-center mt-3 p-3 bg-light rounded">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($ticket->qr_code) }}" 
                                                 alt="QR Code" class="img-fluid" style="max-width: 150px;">
                                            <div class="small text-muted mt-2">Apresente este código no embarque</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-footer bg-white border-top">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary flex-grow-1" 
                                                wire:click="downloadTicket({{ $ticket->id }})">
                                            <i class="ph-download me-1"></i>
                                            Download PDF
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary flex-grow-1" 
                                                wire:click="resendTicket({{ $ticket->id }})">
                                            <i class="ph-paper-plane-tilt me-1"></i>
                                            Reenviar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Nenhum Resultado -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="ph-magnifying-glass text-muted" style="font-size: 80px;"></i>
                                </div>
                                <h4 class="text-muted mb-3">Nenhum bilhete encontrado</h4>
                                <p class="text-muted mb-4">
                                    Não encontramos bilhetes com os dados fornecidos.<br>
                                    Verifique se digitou corretamente e tente novamente.
                                </p>
                                <button class="btn btn-primary" wire:click="resetSearch">
                                    <i class="ph-arrow-left me-2"></i>
                                    Nova Busca
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Info Box -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="ph-shield-check"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-2">Dados Seguros</h5>
                            <p class="text-muted mb-0 small">
                                Suas informações estão protegidas e criptografadas
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="ph-clock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-2">Acesso 24/7</h5>
                            <p class="text-muted mb-0 small">
                                Consulte seus bilhetes a qualquer hora, em qualquer lugar
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="ph-headset"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-2">Suporte Disponível</h5>
                            <p class="text-muted mb-0 small">
                                Nossa equipe está pronta para ajudar você
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ticket-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        }
    </style>
</div>
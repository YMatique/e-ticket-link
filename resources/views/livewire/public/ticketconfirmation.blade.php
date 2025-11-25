<div>
    <!-- Success Header -->
    <section class="bg-success text-white py-5">
        <div class="container text-center">
            <div class="success-icon mb-3">
                <i class="ph-check-circle ph-4x"></i>
            </div>
            <h1 class="mb-2">Compra Realizada com Sucesso!</h1>
            <p class="lead mb-0">Seus bilhetes foram gerados e enviados para seu email e telefone.</p>
        </div>
    </section>

    <div class="container py-5">
        <!-- Informações da Viagem -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="ph-info me-2"></i>
                    Informações da Viagem
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="small text-muted">Rota</div>
                        <div class="fw-semibold">
                            {{ $mainTicket->schedule->route->originCity->name }}
                            <i class="ph-arrow-right mx-1"></i>
                            {{ $mainTicket->schedule->route->destinationCity->name }}
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="small text-muted">Data e Hora</div>
                        <div class="fw-semibold">
                            {{ $mainTicket->schedule->departure_date->format('d/m/Y') }}
                            <br>
                            {{ substr($mainTicket->schedule->departure_time, 0, 5) }}
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="small text-muted">Autocarro</div>
                        <div class="fw-semibold">
                            {{ $mainTicket->schedule->bus->model }}
                            <br>
                            <small class="text-muted">{{ $mainTicket->schedule->bus->registration_number }}</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="small text-muted">Total de Bilhetes</div>
                        <div class="fw-semibold">{{ count($tickets) }} bilhete(s)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bilhetes -->
        <div class="row">
            @foreach($tickets as $ticket)
                <div class="col-md-6 mb-4">
                    <div class="card ticket-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Bilhete #{{ $ticket->ticket_number }}</h6>
                                </div>
                                <div>
                                    <span class="badge bg-white text-primary">
                                        Assento {{ $ticket->seat_number }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- QR Code -->
                            <div class="text-center mb-4">
                                <div class="qr-code-container">
                                    @if($ticket->qr_code)
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($ticket->qr_code) }}" 
                                             alt="QR Code"
                                             class="img-fluid">
                                    @else
                                        <div class="bg-light p-4 rounded">
                                            <i class="ph-qr-code ph-4x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="small text-muted mt-2">
                                    Apresente este QR Code no embarque
                                </div>
                            </div>

                            <!-- Dados do Passageiro -->
                            <div class="passenger-info">
                                <div class="mb-2">
                                    <div class="small text-muted">Passageiro</div>
                                    <div class="fw-semibold">
                                        {{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="small text-muted">Documento</div>
                                    <div>
                                        {{ strtoupper($ticket->passenger->document_type) }}: 
                                        {{ $ticket->passenger->document_number }}
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="small text-muted">Status</div>
                                    <div>
                                        @php
                                            $statusConfig = [
                                                'paid' => ['class' => 'success', 'icon' => 'check-circle', 'label' => 'Pago'],
                                                'reserved' => ['class' => 'warning', 'icon' => 'clock', 'label' => 'Reservado'],
                                                'validated' => ['class' => 'info', 'icon' => 'seal-check', 'label' => 'Validado'],
                                            ];
                                            $status = $statusConfig[$ticket->status] ?? ['class' => 'secondary', 'icon' => 'question', 'label' => $ticket->status];
                                        @endphp
                                        <span class="badge bg-{{ $status['class'] }}">
                                            <i class="ph-{{ $status['icon'] }} me-1"></i>
                                            {{ $status['label'] }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">Preço</div>
                                    <div class="fw-bold text-primary">
                                        {{ number_format($ticket->price, 2) }} MT
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex gap-2">
                                <button wire:click="downloadTicket({{ $ticket->id }})" 
                                        class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="ph-download me-1"></i>
                                    Baixar PDF
                                </button>
                                <button wire:click="sendTicketByEmail({{ $ticket->id }})" 
                                        class="btn btn-sm btn-outline-secondary flex-fill">
                                    <i class="ph-envelope me-1"></i>
                                    Reenviar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Instruções Importantes -->
        <div class="card bg-info bg-opacity-10 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="ph-info me-2"></i>
                    Instruções Importantes
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>Chegue com 30 minutos de antecedência</strong> ao horário de partida</li>
                    <li>Traga seu <strong>documento de identificação</strong> original</li>
                    <li>Apresente o <strong>QR Code do bilhete</strong> no embarque (pode ser no celular ou impresso)</li>
                    @if($mainTicket->status === 'reserved')
                        <li class="text-danger">
                            <strong>ATENÇÃO:</strong> Seu bilhete está RESERVADO. 
                            Você deve efetuar o pagamento no terminal antes do embarque.
                        </li>
                    @endif
                    <li>Bagagem de mão: máximo <strong>10kg</strong></li>
                    <li>Crianças abaixo de 5 anos não pagam (no colo do responsável)</li>
                </ul>
            </div>
        </div>

        <!-- Ações -->
        <div class="text-center mt-4">
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('public.home') }}" class="btn btn-primary">
                    <i class="ph-house me-2"></i>
                    Voltar ao Início
                </a>
                <a 
                {{-- href="{{ route('public.my-tickets') }}"  --}}
                class="btn btn-outline-primary">
                    <i class="ph-ticket me-2"></i>
                    Ver Meus Bilhetes
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="ph-printer me-2"></i>
                    Imprimir Todos
                </button>
            </div>
        </div>

        <!-- Informações de Contato -->
        <div class="card mt-4">
            <div class="card-body text-center">
                <h6 class="mb-3">Precisa de Ajuda?</h6>
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <div>
                        <i class="ph-phone text-primary me-2"></i>
                        <a href="tel:+258841234567">+258 84 123 4567</a>
                    </div>
                    <div>
                        <i class="ph-whatsapp-logo text-success me-2"></i>
                        <a href="https://wa.me/258841234567" target="_blank">WhatsApp</a>
                    </div>
                    <div>
                        <i class="ph-envelope text-info me-2"></i>
                        <a href="mailto:suporte@citylink.co.mz">suporte@citylink.co.mz</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .success-icon {
        animation: successPop 0.5s ease-out;
    }

    @keyframes successPop {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .ticket-card {
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .ticket-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    .qr-code-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        display: inline-block;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Print Styles */
    @media print {
        .navbar, .card-footer, footer, button {
            display: none !important;
        }
        
        .ticket-card {
            page-break-inside: avoid;
            page-break-after: always;
        }
        
        .card {
            border: 1px solid #000;
            box-shadow: none;
        }
    }
</style>
@endpush
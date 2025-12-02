<div class="container py-4">
    <div class="row">
        <div class="col-lg-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('my-tickets') }}" wire:navigate>
                            <i class="ph-ticket me-1"></i>
                            Meus Bilhetes
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
                </ol>
            </nav>

            <!-- Success/Error Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ph-x-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Coluna Principal -->
                <div class="col-lg-8">
                    <!-- Header do Bilhete -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="mb-1">
                                        <i class="ph-ticket text-primary me-2"></i>
                                        {{ $ticket->ticket_number }}
                                    </h4>
                                    <p class="text-muted mb-0">Bilhete de Viagem</p>
                                </div>
                                <div>
                                    @php
                                        $statusColors = [
                                            'reserved' => 'warning',
                                            'paid' => 'success',
                                            'validated' => 'info',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'reserved' => 'Reservado',
                                            'paid' => 'Pago',
                                            'validated' => 'Validado',
                                            'cancelled' => 'Cancelado',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }} fs-6">
                                        {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                                    </span>
                                </div>
                            </div>

                            @if($ticket->isPurchasedForOther())
                                <div class="alert alert-info">
                                    <i class="ph-gift me-2"></i>
                                    <strong>Bilhete comprado para outra pessoa</strong>
                                    <p class="mb-0 small">
                                        Você comprou este bilhete para {{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações da Viagem -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ph-map-trifold me-2"></i>
                                Detalhes da Viagem
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Rota -->
                            <div class="row mb-4">
                                <div class="col-5 text-center">
                                    <i class="ph-map-pin ph-2x text-primary mb-2"></i>
                                    <h5 class="mb-0">{{ $ticket->schedule->route->originCity->name }}</h5>
                                    <small class="text-muted">Origem</small>
                                </div>
                                <div class="col-2 text-center d-flex align-items-center justify-content-center">
                                    <i class="ph-arrow-right ph-2x text-muted"></i>
                                </div>
                                <div class="col-5 text-center">
                                    <i class="ph-flag ph-2x text-success mb-2"></i>
                                    <h5 class="mb-0">{{ $ticket->schedule->route->destinationCity->name }}</h5>
                                    <small class="text-muted">Destino</small>
                                </div>
                            </div>

                            <!-- Informações -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ph-calendar text-muted me-3 fs-4"></i>
                                        <div>
                                            <small class="text-muted d-block">Data de Partida</small>
                                            <strong>{{ $ticket->schedule->departure_date->format('d/m/Y') }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ph-clock text-muted me-3 fs-4"></i>
                                        <div>
                                            <small class="text-muted d-block">Horário</small>
                                            <strong>{{ $ticket->schedule->departure_time }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ph-armchair text-muted me-3 fs-4"></i>
                                        <div>
                                            <small class="text-muted d-block">Assento</small>
                                            <strong class="fs-5">{{ $ticket->seat_number }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ph-bus text-muted me-3 fs-4"></i>
                                        <div>
                                            <small class="text-muted d-block">Autocarro</small>
                                            <strong>{{ $ticket->schedule->bus->model ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $ticket->schedule->bus->plate_number ?? '' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Passageiro -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ph-user me-2"></i>
                                Informações do Passageiro
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Nome Completo</small>
                                    <strong>{{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}</strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block">Email</small>
                                    <strong>{{ $ticket->passenger->email }}</strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block">Telefone</small>
                                    <strong>{{ $ticket->passenger->phone ?? 'N/A' }}</strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block">Documento</small>
                                    <strong>{{ strtoupper($ticket->passenger->document_type) }}: {{ $ticket->passenger->document_number }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- QR Code -->
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <h6 class="mb-3">
                                <i class="ph-qr-code me-2"></i>
                                QR Code do Bilhete
                            </h6>
                            <div class="mb-3">
                                <div id="qrcode" class="d-inline-block p-3 bg-white rounded"></div>
                            </div>
                            <p class="text-muted small mb-0">
                                Apresente este código no embarque
                            </p>
                        </div>
                    </div>

                    <!-- Preço -->
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted d-block mb-2">Valor Total</small>
                            <h2 class="text-primary mb-0">{{ number_format($ticket->price, 2) }} MT</h2>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ph-dots-three me-2"></i>
                                Ações
                            </h6>
                        </div>
                        <div class="list-group list-group-flush">
                            <button type="button" 
                                    class="list-group-item list-group-item-action"
                                    wire:click="downloadPdf"
                                    wire:loading.attr="disabled">
                                <i class="ph-file-pdf me-2 text-danger"></i>
                                <span wire:loading.remove wire:target="downloadPdf">Baixar PDF</span>
                                <span wire:loading wire:target="downloadPdf">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Gerando...
                                </span>
                            </button>

                            <button type="button" 
                                    class="list-group-item list-group-item-action"
                                    wire:click="resendEmail"
                                    wire:loading.attr="disabled">
                                <i class="ph-envelope me-2 text-primary"></i>
                                <span wire:loading.remove wire:target="resendEmail">Reenviar Email</span>
                                <span wire:loading wire:target="resendEmail">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Enviando...
                                </span>
                            </button>

                            @if($canCancel)
                                <button type="button" 
                                        class="list-group-item list-group-item-action text-danger"
                                        wire:click="openCancelModal">
                                    <i class="ph-x-circle me-2"></i>
                                    Cancelar Bilhete
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ph-info me-2"></i>
                                Informações
                            </h6>
                        </div>
                        <div class="card-body">
                            <small class="text-muted d-block mb-2">Data de Emissão</small>
                            <p class="mb-3">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>

                            @if($ticket->validated_at)
                                <small class="text-muted d-block mb-2">Data de Validação</small>
                                <p class="mb-3">{{ $ticket->validated_at->format('d/m/Y H:i') }}</p>
                            @endif

                            @if($ticket->cancelled_at)
                                <small class="text-muted d-block mb-2">Data de Cancelamento</small>
                                <p class="mb-3">{{ $ticket->cancelled_at->format('d/m/Y H:i') }}</p>
                                
                                @if($ticket->cancellation_reason)
                                    <small class="text-muted d-block mb-2">Motivo</small>
                                    <p class="mb-0">{{ $ticket->cancellation_reason }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cancelamento -->
    @if($showCancelModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ph-warning text-warning me-2"></i>
                            Cancelar Bilhete
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeCancelModal"></button>
                    </div>
                    <form wire:submit.prevent="cancelTicket">
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="ph-warning-circle me-2"></i>
                                <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Motivo do Cancelamento *</label>
                                <textarea class="form-control @error('cancelReason') is-invalid @enderror" 
                                          wire:model="cancelReason"
                                          rows="4"
                                          placeholder="Por favor, informe o motivo do cancelamento (mínimo 10 caracteres)"
                                          required></textarea>
                                @error('cancelReason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" 
                                    class="btn btn-light" 
                                    wire:click="closeCancelModal">
                                Fechar
                            </button>
                            <button type="submit" 
                                    class="btn btn-danger"
                                    wire:loading.attr="disabled"
                                    wire:target="cancelTicket">
                                <span wire:loading.remove wire:target="cancelTicket">
                                    <i class="ph-x-circle me-2"></i>
                                    Confirmar Cancelamento
                                </span>
                                <span wire:loading wire:target="cancelTicket">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Cancelando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gerar QR Code
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ $ticket->qr_code }}",
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
@endpush
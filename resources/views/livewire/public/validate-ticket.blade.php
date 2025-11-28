<div>
    <!-- Hero Section -->
    <div class="bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="ph-qr-code ph-2x me-2"></i>
                        Validar Bilhete
                    </h1>
                    <p class="lead mb-0">
                        Escaneie o QR Code ou digite o número do bilhete
                    </p>
                </div>
            </div>
        </div>
    </div>

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

        @if (session()->has('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <i class="ph-warning me-2"></i>
                {{ session('warning') }}
            </div>
        @endif

        @if (session()->has('info'))
            <div class="alert alert-info alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <i class="ph-info me-2"></i>
                {{ session('info') }}
            </div>
        @endif

        <!-- Estatísticas (apenas para usuários autenticados) -->
        @auth
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Estatísticas de Hoje</h5>
                        <button class="btn btn-sm btn-outline-primary" wire:click="refreshStats">
                            <i class="ph-arrows-clockwise me-1"></i>
                            Atualizar
                        </button>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="ph-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="text-muted small">Validados Hoje</div>
                                    <div class="fs-3 fw-bold text-success">{{ $stats['validated_today'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="ph-clock"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="text-muted small">Pendentes Hoje</div>
                                    <div class="fs-3 fw-bold text-warning">{{ $stats['pending_validation'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="ph-x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="text-muted small">No-Show</div>
                                    <div class="fs-3 fw-bold text-danger">{{ $stats['no_show'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endauth

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Scanner Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <!-- Tabs para escolher método -->
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="camera-tab" data-bs-toggle="tab" 
                                        data-bs-target="#camera-pane" type="button" role="tab">
                                    <i class="ph-camera me-2"></i>
                                    Escanear com Câmera
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="manual-tab" data-bs-toggle="tab" 
                                        data-bs-target="#manual-pane" type="button" role="tab">
                                    <i class="ph-keyboard me-2"></i>
                                    Digitar Manualmente
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Tab 1: Scanner com Câmera -->
                            <div class="tab-pane fade show active" id="camera-pane" role="tabpanel">
                                <div class="text-center mb-3">
                                    <i class="ph-camera text-primary" style="font-size: 60px;"></i>
                                    <h5 class="mt-3">Escaneie o QR Code</h5>
                                    <p class="text-muted">Posicione o QR Code na frente da câmera</p>
                                </div>

                                <!-- Leitor de QR Code -->
                                <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                                
                                <!-- Botões de controle -->
                                <div class="text-center mt-3">
                                    <button type="button" id="start-camera" class="btn btn-primary btn-lg">
                                        <i class="ph-camera me-2"></i>
                                        Iniciar Câmera
                                    </button>
                                    <button type="button" id="stop-camera" class="btn btn-danger btn-lg" style="display: none;">
                                        <i class="ph-stop me-2"></i>
                                        Parar Câmera
                                    </button>
                                </div>

                                <div class="alert alert-info mt-4">
                                    <i class="ph-info me-2"></i>
                                    <strong>Dica:</strong> Permita o acesso à câmera quando solicitado pelo navegador.
                                </div>
                            </div>

                            <!-- Tab 2: Input Manual -->
                            <div class="tab-pane fade" id="manual-pane" role="tabpanel">
                                <div class="text-center mb-4">
                                    <i class="ph-keyboard text-primary" style="font-size: 60px;"></i>
                                    <h5 class="mt-3">Digite o Código</h5>
                                </div>

                                <form wire:submit.prevent="searchTicket">
                                    <div class="row">
                                        <div class="col-lg-9 mb-3 mb-lg-0">
                                            <input type="text" 
                                                   class="form-control form-control-lg @error('ticket_code') is-invalid @enderror" 
                                                   wire:model.live="ticket_code"
                                                   placeholder="TKT-20251125-ABC123 ou código do QR"
                                                   autofocus>
                                            @error('ticket_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg w-100" wire:loading.attr="disabled">
                                                <span wire:loading.remove wire:target="searchTicket">
                                                    <i class="ph-magnifying-glass me-2"></i>
                                                    Buscar
                                                </span>
                                                <span wire:loading wire:target="searchTicket">
                                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                                    Buscando...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="alert alert-info mt-4 mb-0">
                                    <i class="ph-info me-2"></i>
                                    <strong>Dica:</strong> Cole o código QR ou digite o número do bilhete.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultado da Validação -->
                @if($searched && $ticket)
                    <!-- Status Alert -->
                    @if($validation_message)
                        <div class="alert alert-{{ $validation_type === 'success' ? 'success' : ($validation_type === 'warning' ? 'warning' : 'danger') }} mb-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ph-{{ $validation_type === 'success' ? 'check-circle' : 'warning-circle' }} fs-1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        @if($validation_type === 'success')
                                            ✅ BILHETE VÁLIDO
                                        @elseif($validation_type === 'warning')
                                            ⚠️ ATENÇÃO
                                        @else
                                            ❌ BILHETE INVÁLIDO
                                        @endif
                                    </h5>
                                    <p class="mb-0">{{ $validation_message }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Detalhes do Bilhete -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="ph-ticket me-2"></i>
                                    Detalhes do Bilhete
                                </h5>
                                <span class="badge {{ $validation_type === 'success' ? 'bg-success' : ($validation_type === 'warning' ? 'bg-warning text-dark' : 'bg-danger') }} fs-6">
                                    {{ strtoupper($ticket->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Número do Bilhete -->
                            <div class="mb-4 p-3 bg-light rounded text-center">
                                <div class="text-muted small mb-1">Número do Bilhete</div>
                                <div class="fs-3 fw-bold text-primary">{{ $ticket->ticket_number }}</div>
                            </div>

                            <!-- Informações do Passageiro -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="ph-user-circle text-primary fs-2 me-3"></i>
                                        <div>
                                            <div class="text-muted small">Passageiro</div>
                                            <div class="fw-bold">
                                                {{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="ph-phone text-primary fs-2 me-3"></i>
                                        <div>
                                            <div class="text-muted small">Telefone</div>
                                            <div class="fw-bold">{{ $ticket->passenger->phone }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações da Viagem -->
                            <div class="border-top pt-4 mb-4">
                                <h6 class="text-muted mb-3">Informações da Viagem</h6>
                                
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="text-center">
                                        <div class="fw-bold fs-4">{{ $ticket->schedule->route->originCity->name }}</div>
                                        <small class="text-muted">{{ $ticket->schedule->departure_time }}</small>
                                    </div>
                                    <div class="px-4">
                                        <i class="ph-arrow-right text-primary" style="font-size: 32px;"></i>
                                    </div>
                                    <div class="text-center">
                                        <div class="fw-bold fs-4">{{ $ticket->schedule->route->destinationCity->name }}</div>
                                        <small class="text-muted">{{ $ticket->schedule->arrival_time }}</small>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-6 col-md-3">
                                        <div class="text-center p-3 bg-light rounded">
                                            <div class="text-muted small">Data</div>
                                            <div class="fw-bold">{{ $ticket->schedule->departure_date->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="text-center p-3 bg-light rounded">
                                            <div class="text-muted small">Assento</div>
                                            <div class="fw-bold">{{ $ticket->seat_number }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="text-center p-3 bg-light rounded">
                                            <div class="text-muted small">Autocarro</div>
                                            <div class="fw-bold">{{ $ticket->schedule->bus->registration_number }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="text-center p-3 bg-light rounded">
                                            <div class="text-muted small">Preço</div>
                                            <div class="fw-bold">{{ number_format($ticket->price, 2) }} MT</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info de Validação (se já validado) -->
                            @if($ticket->status === 'validated' && $ticket->validated_at)
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <i class="ph-info me-2"></i>
                                        <div>
                                            <strong>Já Validado:</strong> 
                                            {{ $ticket->validated_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Ações -->
                            <div class="d-flex gap-2 mt-4">
                                @if($validation_type === 'success' && $ticket->status !== 'validated')
                                    <button class="btn btn-success btn-lg flex-grow-1" wire:click="validateTicket">
                                        <i class="ph-check-circle me-2"></i>
                                        Validar e Permitir Embarque
                                    </button>
                                @endif
                                
                                <button class="btn btn-outline-secondary btn-lg" wire:click="cancelValidation">
                                    <i class="ph-x me-2"></i>
                                    {{ $validation_type === 'success' && $ticket->status !== 'validated' ? 'Cancelar' : 'Novo' }}
                                </button>
                            </div>
                        </div>
                    </div>
                @elseif($searched && !$ticket && $validation_message)
                    <!-- Bilhete não encontrado -->
                    <div class="alert alert-danger mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="ph-warning-circle fs-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">❌ Bilhete Não Encontrado</h5>
                                <p class="mb-0">{{ $validation_message }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" wire:click="cancelValidation">
                            <i class="ph-arrow-left me-2"></i>
                            Tentar Novamente
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Loading animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .spinner-border {
            animation: spin 0.75s linear infinite;
        }

        /* Hover effect */
        .card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* QR Reader styling */
        #qr-reader {
            border: 2px dashed #667eea;
            border-radius: 10px;
            overflow: hidden;
        }

        #qr-reader video {
            width: 100%;
            border-radius: 8px;
        }

        #qr-reader__dashboard_section_swaplink {
            display: none !important;
        }
    </style>

    <!-- HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode;
        let isScanning = false;

        // Botões
        const startBtn = document.getElementById('start-camera');
        const stopBtn = document.getElementById('stop-camera');

        // Função para iniciar câmera
        startBtn?.addEventListener('click', async function() {
            if (isScanning) return;

            try {
                // Criar instância do scanner
                html5QrCode = new Html5Qrcode("qr-reader");

                // Configurações
                const config = {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };

                // Iniciar scanning
                await html5QrCode.start(
                    { facingMode: "environment" }, // Câmera traseira
                    config,
                    onScanSuccess,
                    onScanFailure
                );

                isScanning = true;
                startBtn.style.display = 'none';
                stopBtn.style.display = 'inline-block';

            } catch (err) {
                console.error('Erro ao iniciar câmera:', err);
                alert('Erro ao acessar câmera. Verifique as permissões do navegador.');
            }
        });

        // Função para parar câmera
        stopBtn?.addEventListener('click', async function() {
            if (!isScanning) return;

            try {
                await html5QrCode.stop();
                html5QrCode.clear();
                
                isScanning = false;
                startBtn.style.display = 'inline-block';
                stopBtn.style.display = 'none';
            } catch (err) {
                console.error('Erro ao parar câmera:', err);
            }
        });

        // Callback quando QR Code é lido com sucesso
        function onScanSuccess(decodedText, decodedResult) {
            console.log('QR Code detectado:', decodedText);

            // Parar câmera
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    startBtn.style.display = 'inline-block';
                    stopBtn.style.display = 'none';
                }).catch(err => {
                    console.error('Erro ao parar câmera:', err);
                });
            }

            // Enviar para Livewire
            @this.set('ticket_code', decodedText);
            @this.call('searchTicket');

            // Mudar para tab manual para mostrar resultado
            setTimeout(() => {
                const manualTab = document.getElementById('manual-tab');
                if (manualTab) {
                    const tab = new bootstrap.Tab(manualTab);
                    tab.show();
                }
            }, 500);
        }

        // Callback para erros (opcional - não faz nada, é normal ter muitos erros)
        function onScanFailure(error) {
            // Ignora erros - é normal enquanto busca o QR Code
            // console.warn('Erro de scan:', error);
        }

        // Limpar ao trocar de tab
        document.getElementById('camera-tab')?.addEventListener('shown.bs.tab', function() {
            // Quando voltar para câmera, não faz nada
        });

        document.getElementById('manual-tab')?.addEventListener('shown.bs.tab', function() {
            // Quando ir para manual, parar câmera
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    startBtn.style.display = 'inline-block';
                    stopBtn.style.display = 'none';
                }).catch(err => {
                    console.error('Erro ao parar câmera:', err);
                });
            }
        });

        // Limpar quando sair da página
        window.addEventListener('beforeunload', function() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop();
            }
        });
    </script>
</div>
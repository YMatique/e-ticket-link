<div>
    <!-- Progress Steps -->
    <section class="bg-light py-3 border-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Step 1 -->
                        <div class="text-center flex-fill">
                            <div class="step-circle {{ $step >= 1 ? 'active' : '' }}">
                                @if($step > 1)
                                    <i class="ph-check"></i>
                                @else
                                    1
                                @endif
                            </div>
                            <div class="small mt-2">Dados dos Passageiros</div>
                        </div>
                        <div class="step-line {{ $step >= 2 ? 'active' : '' }}"></div>
                        
                        <!-- Step 2 -->
                        <div class="text-center flex-fill">
                            <div class="step-circle {{ $step >= 2 ? 'active' : '' }}">
                                @if($step > 2)
                                    <i class="ph-check"></i>
                                @else
                                    2
                                @endif
                            </div>
                            <div class="small mt-2">Pagamento</div>
                        </div>
                        <div class="step-line {{ $step >= 3 ? 'active' : '' }}"></div>
                        
                        <!-- Step 3 -->
                        <div class="text-center flex-fill">
                            <div class="step-circle {{ $step >= 3 ? 'active' : '' }}">3</div>
                            <div class="small mt-2">Confirmação</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Formulário -->
            <div class="col-lg-8">
                @if($step === 1)
                    <!-- Passo 1: Dados dos Passageiros -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="ph-user me-2"></i>
                                Dados dos Passageiros
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($passengers as $index => $passenger)
                                <div class="passenger-section mb-4 pb-4 {{ $index < count($passengers) - 1 ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="badge bg-primary me-2">
                                            Assento {{ $passenger['seat_number'] }}
                                        </div>
                                        <h6 class="mb-0">Passageiro {{ $index + 1 }}</h6>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Primeiro Nome <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   wire:model.blur="passengers.{{ $index }}.first_name"
                                                   class="form-control @error('passengers.'.$index.'.first_name') is-invalid @enderror"
                                                   placeholder="Ex: João">
                                            @error('passengers.'.$index.'.first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Último Nome <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   wire:model.blur="passengers.{{ $index }}.last_name"
                                                   class="form-control @error('passengers.'.$index.'.last_name') is-invalid @enderror"
                                                   placeholder="Ex: Silva">
                                            @error('passengers.'.$index.'.last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                                            <select wire:model.live="passengers.{{ $index }}.document_type"
                                                    class="form-select @error('passengers.'.$index.'.document_type') is-invalid @enderror">
                                                <option value="bi">Bilhete de Identidade</option>
                                                <option value="passport">Passaporte</option>
                                                <option value="birth_certificate">Certidão de Nascimento</option>
                                            </select>
                                            @error('passengers.'.$index.'.document_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Número do Documento <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   wire:model.blur="passengers.{{ $index }}.document_number"
                                                   class="form-control @error('passengers.'.$index.'.document_number') is-invalid @enderror"
                                                   placeholder="Ex: 110200012345X">
                                            @error('passengers.'.$index.'.document_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Dados de Contato -->
                            <div class="mt-4 pt-4 border-top">
                                <h6 class="mb-3">Dados de Contato</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               wire:model.blur="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="seuemail@exemplo.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Os bilhetes serão enviados para este email</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Telefone <span class="text-danger">*</span></label>
                                        <input type="tel" 
                                               wire:model.blur="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               placeholder="84 123 4567">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Receberá SMS com os bilhetes</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Criar Conta -->
                            <div class="mt-3">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           wire:model.live="create_account"
                                           class="form-check-input" 
                                           id="create_account">
                                    <label class="form-check-label" for="create_account">
                                        Criar uma conta para acompanhar minhas viagens
                                    </label>
                                </div>

                                @if($create_account)
                                    <div class="mt-3">
                                        <label class="form-label">Senha <span class="text-danger">*</span></label>
                                        <input type="password" 
                                               wire:model.blur="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Mínimo 8 caracteres">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <!-- Termos -->
                            <div class="mt-4">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           wire:model.live="accept_terms"
                                           class="form-check-input @error('accept_terms') is-invalid @enderror" 
                                           id="accept_terms">
                                    <label class="form-check-label" for="accept_terms">
                                        Aceito os <a href="#" target="_blank">termos e condições</a> <span class="text-danger">*</span>
                                    </label>
                                    @error('accept_terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <button wire:click="goToPayment" 
                                    class="btn btn-primary btn-lg w-100"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="goToPayment">
                                    Continuar para Pagamento
                                    <i class="ph-arrow-right ms-2"></i>
                                </span>
                                <span wire:loading wire:target="goToPayment">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Validando...
                                </span>
                            </button>
                        </div>
                    </div>

                @elseif($step === 2)
                    <!-- Passo 2: Pagamento -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="ph-credit-card me-2"></i>
                                Método de Pagamento
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Métodos de Pagamento -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <input type="radio" 
                                           wire:model.live="payment_method" 
                                           value="mpesa" 
                                           class="btn-check" 
                                           id="mpesa">
                                    <label class="btn btn-outline-primary w-100 py-3" for="mpesa">
                                        <i class="ph-device-mobile ph-2x d-block mb-2"></i>
                                        M-Pesa
                                    </label>
                                </div>

                                <div class="col-md-4">
                                    <input type="radio" 
                                           wire:model.live="payment_method" 
                                           value="emola" 
                                           class="btn-check" 
                                           id="emola">
                                    <label class="btn btn-outline-success w-100 py-3" for="emola">
                                        <i class="ph-device-mobile ph-2x d-block mb-2"></i>
                                        e-Mola
                                    </label>
                                </div>

                                <div class="col-md-4">
                                    <input type="radio" 
                                           wire:model.live="payment_method" 
                                           value="cash" 
                                           class="btn-check" 
                                           id="cash">
                                    <label class="btn btn-outline-secondary w-100 py-3" for="cash">
                                        <i class="ph-money ph-2x d-block mb-2"></i>
                                        Dinheiro
                                    </label>
                                </div>
                            </div>

                            <!-- Detalhes do Pagamento -->
                            @if($payment_method === 'mpesa')
                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <i class="ph-info ph-2x me-3"></i>
                                        <div>
                                            <strong>Como pagar com M-Pesa:</strong>
                                            <ol class="mb-0 mt-2 ps-3">
                                                <li>Insira seu número M-Pesa abaixo</li>
                                                <li>Receberá uma notificação no seu telefone</li>
                                                <li>Insira seu PIN M-Pesa para confirmar</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Número M-Pesa <span class="text-danger">*</span></label>
                                    <input type="tel" 
                                           wire:model.blur="mpesa_number"
                                           class="form-control @error('mpesa_number') is-invalid @enderror"
                                           placeholder="84 123 4567">
                                    @error('mpesa_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            @elseif($payment_method === 'emola')
                                <div class="alert alert-success">
                                    <div class="d-flex">
                                        <i class="ph-info ph-2x me-3"></i>
                                        <div>
                                            <strong>Pagamento via e-Mola</strong>
                                            <p class="mb-0 mt-2">Será redirecionado para o portal e-Mola para completar o pagamento.</p>
                                        </div>
                                    </div>
                                </div>

                            @elseif($payment_method === 'cash')
                                <div class="alert alert-warning">
                                    <div class="d-flex">
                                        <i class="ph-warning ph-2x me-3"></i>
                                        <div>
                                            <strong>Pagamento em Dinheiro</strong>
                                            <p class="mb-2 mt-2">Seus bilhetes serão reservados. Você deve pagar no terminal antes da partida.</p>
                                            <p class="mb-0"><strong>Importante:</strong> Chegue com 30 minutos de antecedência para efetuar o pagamento.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex gap-2">
                                <button wire:click="backToInfo" 
                                        class="btn btn-outline-secondary"
                                        wire:loading.attr="disabled">
                                    <i class="ph-arrow-left me-2"></i>
                                    Voltar
                                </button>
                                <button wire:click="processPayment" 
                                        class="btn btn-primary flex-fill btn-lg"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="processPayment">
                                        @if($payment_method === 'cash')
                                            Confirmar Reserva
                                        @else
                                            Processar Pagamento - {{ number_format($totalPrice, 2) }} MT
                                        @endif
                                        <i class="ph-check ms-2"></i>
                                    </span>
                                    <span wire:loading wire:target="processPayment">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Processando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                @elseif($step === 3)
                    <!-- Passo 3: Processando -->
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                            <h4 class="mb-2">Processando seu pagamento...</h4>
                            <p class="text-muted">Por favor, aguarde. Não feche esta página.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Resumo Lateral -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="ph-receipt me-2"></i>
                            Resumo da Compra
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Viagem -->
                        <div class="mb-3">
                            <div class="small text-muted mb-2">Viagem</div>
                            <div class="fw-semibold">
                                {{ $schedule->route->originCity->name }}
                                <i class="ph-arrow-right mx-1"></i>
                                {{ $schedule->route->destinationCity->name }}
                            </div>
                            <div class="small text-muted">
                                {{ $schedule->departure_date->format('d/m/Y') }} às {{ substr($schedule->departure_time, 0, 5) }}
                            </div>
                        </div>

                        <hr>

                        <!-- Assentos -->
                        <div class="mb-3">
                            <div class="small text-muted mb-2">Assentos Selecionados</div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($seats as $seat)
                                    <span class="badge bg-primary">{{ $seat }}</span>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <!-- Cálculo -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ count($seats) }} bilhete(s)</span>
                                <span>{{ number_format($schedule->price, 2) }} MT cada</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span>{{ number_format($totalPrice, 2) }} MT</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h6 mb-0">Total a Pagar:</span>
                            <span class="h4 mb-0 text-primary fw-bold">
                                {{ number_format($totalPrice, 2) }} MT
                            </span>
                        </div>
                    </div>

                    <!-- Timer -->
                    <div class="card-footer bg-light" x-data="{ 
                        expiry: {{ $reservationExpiry }},
                        remaining: '',
                        interval: null,
                        init() {
                            this.updateTimer();
                            this.interval = setInterval(() => this.updateTimer(), 1000);
                        },
                        updateTimer() {
                            const now = Math.floor(Date.now() / 1000);
                            const diff = this.expiry - now;
                            if (diff <= 0) {
                                this.remaining = 'Expirado';
                                clearInterval(this.interval);
                                window.location.href = '{{ route('public.home') }}';
                            } else {
                                const minutes = Math.floor(diff / 60);
                                const seconds = diff % 60;
                                this.remaining = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                            }
                        }
                    }">
                        <div class="d-flex align-items-center small">
                            <i class="ph-clock me-2"></i>
                            <div>
                                <div class="text-muted">Tempo restante:</div>
                                <div class="fw-bold" x-text="remaining"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 3px solid #dee2e6;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        background: white;
        color: #6c757d;
    }

    .step-circle.active {
        border-color: #667eea;
        background: #667eea;
        color: white;
    }

    .step-line {
        height: 3px;
        background: #dee2e6;
        flex: 1;
        margin: 0 10px;
    }

    .step-line.active {
        background: #667eea;
    }

    .passenger-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
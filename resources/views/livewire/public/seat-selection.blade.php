<div>
    <!-- Header da Viagem -->
    <section class="bg-light py-3 border-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('public.trips', [
                            'origin' => $schedule->route->origin_city_id,
                            'destination' => $schedule->route->destination_city_id,
                            'date' => $schedule->departure_date->format('Y-m-d'),
                            'passengers' => $passengers
                        ]) }}" class="btn btn-light btn-sm me-3">
                            <i class="ph-arrow-left"></i>
                        </a>
                        <div>
                            <h5 class="mb-1">
                                {{ $schedule->route->originCity->name }}
                                <i class="ph-arrow-right mx-2"></i>
                                {{ $schedule->route->destinationCity->name }}
                            </h5>
                            <div class="small text-muted">
                                <i class="ph-calendar me-1"></i>
                                {{ $schedule->departure_date->format('d/m/Y') }} às {{ substr($schedule->departure_time, 0, 5) }}
                                <span class="mx-2">|</span>
                                <i class="ph-bus me-1"></i>
                                {{ $schedule->bus->model }} ({{ $schedule->bus->registration_number }})
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="small text-muted mb-1">Preço por assento</div>
                    <div class="h5 mb-0 text-primary fw-bold">
                        {{ number_format($schedule->price, 2) }} MT
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Mapa de Assentos -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="ph-armchair me-2"></i>
                            Selecione seus Assentos
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Legenda -->
                        <div class="d-flex justify-content-center gap-4 mb-4 flex-wrap">
                            <div class="d-flex align-items-center">
                                <div class="seat-legend available me-2"></div>
                                <span class="small">Disponível</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="seat-legend selected me-2"></div>
                                <span class="small">Selecionado</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="seat-legend occupied me-2"></div>
                                <span class="small">Ocupado</span>
                            </div>
                        </div>

                        <!-- Bus Layout -->
                        <div class="bus-container">
                            <!-- Driver Area -->
                            <div class="driver-area mb-4">
                                <div class="driver-seat">
                                    <i class="ph-steering-wheel ph-lg"></i>
                                    <div class="small">Motorista</div>
                                </div>
                            </div>

                            <!-- Seats Grid -->
                            <div class="seats-area">
                                @foreach($seatLayout as $rowIndex => $row)
                                    <div class="seat-row">
                                        <!-- Row Number -->
                                        <div class="row-number">{{ $rowIndex + 1 }}</div>

                                        <!-- Seats -->
                                        <div class="seats-container">
                                            @foreach($row as $seat)
                                                @if($seat['col'] == 3)
                                                    <!-- Corredor -->
                                                    <div class="seat-corridor"></div>
                                                @endif
                                                
                                                <div class="seat {{ $seat['status'] }}"
                                                     wire:click="toggleSeat('{{ $seat['number'] }}')"
                                                     wire:loading.class="loading"
                                                     style="cursor: {{ in_array($seat['status'], ['occupied', 'temporary']) ? 'not-allowed' : 'pointer' }}">
                                                    <div class="seat-number">{{ $seat['number'] }}</div>
                                                    @if($seat['status'] === 'occupied' || $seat['status'] === 'temporary')
                                                        <i class="ph-x seat-icon"></i>
                                                    @elseif($seat['status'] === 'selected')
                                                        <i class="ph-check seat-icon"></i>
                                                    @else
                                                        <i class="ph-armchair seat-icon"></i>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Timer de Reserva -->
                        @if(count($selectedSeats) > 0)
                            <div class="alert alert-info mt-4 mb-0" x-data="{ 
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
                                        window.location.reload();
                                    } else {
                                        const minutes = Math.floor(diff / 60);
                                        const seconds = diff % 60;
                                        this.remaining = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                    }
                                }
                            }">
                                <div class="d-flex align-items-center">
                                    <i class="ph-clock ph-lg me-3"></i>
                                    <div>
                                        <strong>Tempo restante:</strong> 
                                        <span x-text="remaining" class="fw-bold"></span>
                                        <div class="small">Complete a compra antes que o tempo expire</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Resumo da Reserva -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="ph-shopping-cart me-2"></i>
                            Resumo da Reserva
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Passageiros -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Passageiros:</span>
                                <span class="fw-semibold">{{ $passengers }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Preço unitário:</span>
                                <span class="fw-semibold">{{ number_format($schedule->price, 2) }} MT</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Assentos Selecionados -->
                        <div class="mb-3">
                            <div class="text-muted small mb-2">Assentos Selecionados:</div>
                            @if(count($selectedSeats) > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($selectedSeats as $seat)
                                        <span class="badge bg-primary">{{ $seat }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3 bg-light rounded">
                                    <i class="ph-armchair ph-2x text-muted mb-2"></i>
                                    <div class="small text-muted">
                                        Nenhum assento selecionado
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <!-- Total -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 mb-0">Total:</span>
                                <span class="h4 mb-0 text-primary fw-bold">
                                    {{ number_format($totalPrice, 2) }} MT
                                </span>
                            </div>
                        </div>

                        <!-- Botão de Continuar -->
                        <button wire:click="proceedToCheckout" 
                                class="btn btn-primary w-100 py-3"
                                @if(!$canProceed) disabled @endif>
                            <i class="ph-arrow-right me-2"></i>
                            Continuar para Pagamento
                        </button>

                        @if(!$canProceed)
                            <div class="alert alert-warning mt-3 mb-0">
                                <small>
                                    <i class="ph-warning me-1"></i>
                                    Selecione {{ $passengers - count($selectedSeats) }} assento(s) para continuar
                                </small>
                            </div>
                        @endif
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="card-footer bg-light">
                        <div class="small text-muted">
                            <div class="mb-2">
                                <i class="ph-shield-check me-2 text-success"></i>
                                Pagamento 100% seguro
                            </div>
                            <div class="mb-2">
                                <i class="ph-clock me-2 text-info"></i>
                                Confirmação instantânea
                            </div>
                            <div>
                                <i class="ph-device-mobile me-2 text-primary"></i>
                                Bilhete enviado por SMS/Email
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
    /* Bus Container */
    .bus-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px 20px;
        background: linear-gradient(to bottom, #f8f9fa 0%, #fff 100%);
        border-radius: 20px;
        border: 3px solid #dee2e6;
    }

    /* Driver Area */
    .driver-area {
        text-align: center;
        padding: 15px;
        background: white;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
        margin-bottom: 20px;
    }

    .driver-seat {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        padding: 10px 20px;
        background: #e9ecef;
        border-radius: 8px;
        color: #495057;
    }

    /* Seats Area */
    .seats-area {
        background: white;
        padding: 20px;
        border-radius: 15px;
    }

    .seat-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .row-number {
        width: 30px;
        text-align: center;
        font-weight: bold;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .seats-container {
        display: flex;
        gap: 10px;
        flex: 1;
        justify-content: center;
    }

    .seat-corridor {
        width: 30px;
    }

    /* Seat Styles */
    .seat {
        width: 50px;
        height: 60px;
        border-radius: 12px 12px 8px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px solid;
        position: relative;
        transition: all 0.3s ease;
        gap: 5px;
    }

    .seat-number {
        font-size: 0.75rem;
        font-weight: bold;
    }

    .seat-icon {
        font-size: 1.2rem;
    }

    /* Seat States */
    .seat.available {
        background: #e8f5e9;
        border-color: #4caf50;
        color: #2e7d32;
    }

    .seat.available:hover {
        background: #c8e6c9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
    }

    .seat.selected {
        background: #2196f3;
        border-color: #1976d2;
        color: white;
        animation: pulse 0.5s;
    }

    .seat.occupied, .seat.temporary {
        background: #ffebee;
        border-color: #f44336;
        color: #c62828;
        cursor: not-allowed !important;
        opacity: 0.6;
    }

    .seat.loading {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Seat Legend */
    .seat-legend {
        width: 30px;
        height: 35px;
        border-radius: 8px 8px 4px 4px;
        border: 2px solid;
        display: inline-block;
    }

    .seat-legend.available {
        background: #e8f5e9;
        border-color: #4caf50;
    }

    .seat-legend.selected {
        background: #2196f3;
        border-color: #1976d2;
    }

    .seat-legend.occupied {
        background: #ffebee;
        border-color: #f44336;
    }

    /* Animations */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    /* Responsive */
    @media (max-width: 576px) {
        .seat {
            width: 40px;
            height: 50px;
        }
        
        .seats-container {
            gap: 8px;
        }
        
        .bus-container {
            padding: 20px 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
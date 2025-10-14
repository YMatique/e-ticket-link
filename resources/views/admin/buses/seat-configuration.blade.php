@extends('layouts.app')

@section('title', 'Configuração de Assentos')

@section('breadcrumbs')
    <span class="breadcrumb-item">Gestão de Viagens</span>
    <a href="{{ route('buses.index') }}" class="breadcrumb-item">Autocarros</a>
    <a href="{{ route('buses.show', $bus) }}" class="breadcrumb-item">{{ $bus->registration_number }}</a>
    <span class="breadcrumb-item active">Configuração de Assentos</span>
@endsection

@section('page-title')
    <i class="ph-chair me-2"></i>
    Configuração de Assentos - {{ $bus->displayName() }}
@endsection

@section('header-actions')
    <a href="{{ route('buses.show', $bus) }}" class="btn btn-light me-2">
        <i class="ph-arrow-left me-2"></i>
        Voltar
    </a>
    <button type="button" class="btn btn-primary" id="saveSeatConfiguration">
        <i class="ph-floppy-disk me-2"></i>
        Salvar Configuração
    </button>
@endsection

@section('content')
    <div class="row">
        <!-- Painel de Configuração -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-info me-2"></i>
                        Informações do Autocarro
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Matrícula:</td>
                            <td class="fw-semibold">{{ $bus->registration_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Modelo:</td>
                            <td class="fw-semibold">{{ $bus->model }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total de Lugares:</td>
                            <td>
                                <span class="badge bg-info">{{ $bus->total_seats }} lugares</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Legenda -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-palette me-2"></i>
                        Legenda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="seat-legend-item bg-primary me-2"></div>
                        <span>Assento Padrão</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="seat-legend-item bg-success me-2"></div>
                        <span>Assento Disponível</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="seat-legend-item bg-secondary me-2"></div>
                        <span>Assento Bloqueado</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="seat-legend-item bg-warning me-2"></div>
                        <span>Assento Especial</span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-lightning me-2"></i>
                        Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" id="resetConfiguration">
                            <i class="ph-arrow-counter-clockwise me-2"></i>
                            Restaurar Padrão
                        </button>
                        <button type="button" class="btn btn-outline-info" id="previewConfiguration">
                            <i class="ph-eye me-2"></i>
                            Pré-visualizar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-chart-bar me-2"></i>
                        Estatísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Total de Assentos</small>
                        <div class="fw-semibold" id="totalSeats">{{ $bus->total_seats }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Assentos Disponíveis</small>
                        <div class="fw-semibold text-success" id="availableSeats">0</div>
                    </div>
                    <div>
                        <small class="text-muted">Assentos Bloqueados</small>
                        <div class="fw-semibold text-secondary" id="blockedSeats">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa de Assentos -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-chair me-2"></i>
                        Layout de Assentos
                    </h5>
                    <div class="ms-auto">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="editMode">
                            <label class="form-check-label" for="editMode">Modo Edição</label>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <!-- Motorista -->
                    <div class="text-center mb-4">
                        <div class="driver-seat">
                            <i class="ph-steering-wheel ph-2x"></i>
                            <div class="small fw-semibold mt-1">MOTORISTA</div>
                        </div>
                    </div>

                    <!-- Mapa de Assentos -->
                    <div id="seatMap" class="seat-map-container">
                        @if($bus->seat_configuration)
                            @foreach($bus->seat_configuration as $rowKey => $rowSeats)
                                <div class="seat-row" data-row="{{ $rowKey }}">
                                    @foreach($rowSeats as $seat)
                                        <div class="seat-item {{ $seat['available'] ?? true ? 'available' : 'blocked' }}" 
                                             data-seat="{{ $seat['number'] }}"
                                             data-position="{{ $seat['position'] ?? 'center' }}"
                                             data-type="{{ $seat['type'] ?? 'standard' }}"
                                             data-available="{{ $seat['available'] ?? true ? 1 : 0 }}">
                                            <div class="seat-number">{{ $seat['number'] }}</div>
                                        </div>
                                        @if(isset($seat['position']) && $seat['position'] == 'left' && !$loop->last)
                                            <div class="seat-aisle"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="ph-warning ph-3x mb-3 d-block"></i>
                                <p>Configuração de assentos não encontrada</p>
                                <button type="button" class="btn btn-primary" id="generateDefault">
                                    <i class="ph-plus me-2"></i>
                                    Gerar Configuração Padrão
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Informações -->
                    <div class="alert alert-info border-0 mt-3">
                        <i class="ph-info me-2"></i>
                        <strong>Modo Edição:</strong> Clique nos assentos para bloquear/desbloquear ou alterar tipo.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Assento -->
    <div class="modal fade" id="editSeatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ph-chair me-2"></i>
                        Editar Assento <span id="editSeatNumber"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Assento</label>
                        <select class="form-select" id="seatType">
                            <option value="standard">Padrão</option>
                            <option value="special">Especial (Ex: Deficientes)</option>
                            <option value="vip">VIP</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="seatAvailable">
                            <label class="form-check-label" for="seatAvailable">Assento Disponível</label>
                        </div>
                        <small class="text-muted">Desmarque para bloquear este assento permanentemente</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveSeatEdit">
                        <i class="ph-check me-2"></i>
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.seat-map-container {
    padding: 20px;
    background: #fff;
    border-radius: 8px;
}

.seat-row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.seat-item {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.seat-item.available {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.seat-item.blocked {
    background: #6c757d;
    color: white;
    border-color: #6c757d;
    opacity: 0.5;
}

.seat-item.special {
    background: #ffc107;
    color: #000;
    border-color: #ffc107;
}

.seat-item.vip {
    background: #198754;
    color: white;
    border-color: #198754;
}

.seat-item:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.seat-item.blocked:hover {
    transform: scale(1);
}

.seat-number {
    font-weight: bold;
    font-size: 14px;
}

.seat-aisle {
    width: 40px;
}

.driver-seat {
    display: inline-block;
    padding: 15px 30px;
    background: #343a40;
    color: white;
    border-radius: 8px;
}

.seat-legend-item {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    border: 2px solid #dee2e6;
}

.edit-mode .seat-item {
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let editMode = false;
    let currentSeatConfiguration = @json($bus->seat_configuration ?? []);
    let currentEditingSeat = null;

    // Toggle Edit Mode
    $('#editMode').on('change', function() {
        editMode = $(this).is(':checked');
        if (editMode) {
            $('#seatMap').addClass('edit-mode');
            toast.info('Modo de edição ativado. Clique nos assentos para editar.');
        } else {
            $('#seatMap').removeClass('edit-mode');
        }
    });

    // Click on Seat
    $(document).on('click', '.seat-item', function() {
        if (!editMode) return;

        currentEditingSeat = $(this);
        const seatNumber = $(this).data('seat');
        const seatType = $(this).data('type');
        const seatAvailable = $(this).data('available');

        $('#editSeatNumber').text(seatNumber);
        $('#seatType').val(seatType);
        $('#seatAvailable').prop('checked', seatAvailable == 1);

        $('#editSeatModal').modal('show');
    });

    // Save Seat Edit
    $('#saveSeatEdit').on('click', function() {
        if (!currentEditingSeat) return;

        const type = $('#seatType').val();
        const available = $('#seatAvailable').is(':checked');

        // Update visual
        currentEditingSeat.removeClass('available blocked special vip');
        currentEditingSeat.addClass(available ? type : 'blocked');
        currentEditingSeat.data('type', type);
        currentEditingSeat.data('available', available ? 1 : 0);

        // Update configuration object
        const row = currentEditingSeat.closest('.seat-row').data('row');
        const seatNumber = currentEditingSeat.data('seat');
        
        if (currentSeatConfiguration[row]) {
            const seatIndex = currentSeatConfiguration[row].findIndex(s => s.number == seatNumber);
            if (seatIndex !== -1) {
                currentSeatConfiguration[row][seatIndex].type = type;
                currentSeatConfiguration[row][seatIndex].available = available;
            }
        }

        updateStatistics();
        $('#editSeatModal').modal('hide');
        toast.success('Assento atualizado!');
    });

    // Save Configuration
    $('#saveSeatConfiguration').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();

        if (confirm('Deseja salvar esta configuração de assentos?')) {
            btn.prop('disabled', true).html('<i class="ph-spinner ph-spin me-2"></i>Salvando...');

            $.ajax({
                url: '{{ route("buses.update-seat-configuration", $bus) }}',
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    seat_configuration: currentSeatConfiguration
                },
                success: function(response) {
                    if (response.success) {
                        toast.success(response.message);
                    } else {
                        toast.error(response.message);
                    }
                },
                error: function(xhr) {
                    toast.error(xhr.responseJSON?.message || 'Erro ao salvar configuração');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        }
    });

    // Reset Configuration
    $('#resetConfiguration').on('click', function() {
        if (confirm('Deseja restaurar a configuração padrão? Todas as alterações serão perdidas.')) {
            location.reload();
        }
    });

    // Update Statistics
    function updateStatistics() {
        let available = 0;
        let blocked = 0;

        $('.seat-item').each(function() {
            if ($(this).data('available') == 1) {
                available++;
            } else {
                blocked++;
            }
        });

        $('#availableSeats').text(available);
        $('#blockedSeats').text(blocked);
    }

    // Initial statistics
    updateStatistics();

    // Preview Configuration
    $('#previewConfiguration').on('click', function() {
        toast.info('Pré-visualização: Esta é a configuração atual dos assentos');
    });
});
</script>
@endpush
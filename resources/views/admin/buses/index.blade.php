@extends('layouts.app')

@section('title', 'Autocarros')

@section('breadcrumbs')
    <span class="breadcrumb-item active">Gestão de Viagens</span>
    <span class="breadcrumb-item active">Autocarros</span>
@endsection

@section('page-title', 'Autocarros')

@section('header-actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBusModal">
        <i class="ph-plus me-2"></i>
        Novo Autocarro
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-3">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['total_buses'] }}</h4>
                        <span class="text-muted">Total de Autocarros</span>
                    </div>
                    <i class="ph-bus ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['active_buses'] }}</h4>
                        <span class="text-muted">Autocarros Ativos</span>
                    </div>
                    <i class="ph-check-circle ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['inactive_buses'] }}</h4>
                        <span class="text-muted">Inativos/Manutenção</span>
                    </div>
                    <i class="ph-wrench ph-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['total_seats'] }}</h4>
                        <span class="text-muted">Total de Lugares</span>
                    </div>
                    <i class="ph-users ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('buses.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Modelo ou matrícula..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="model" class="form-control" placeholder="Ex: Mercedes Sprinter" value="{{ request('model') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="ph-funnel me-1"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('buses.index') }}" class="btn btn-light">
                        <i class="ph-x"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Buses Table -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Lista de Autocarros</h5>
            <div class="ms-auto">
                <span class="badge bg-primary">{{ $buses->total() }} registros</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Matrícula</th>
                        <th>Modelo</th>
                        <th style="width: 100px;" class="text-center">Lugares</th>
                        <th style="width: 100px;" class="text-center">Status</th>
                        <th style="width: 150px;" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buses as $bus)
                        <tr>
                            <td class="fw-semibold">{{ $bus->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ph-bus text-primary me-2"></i>
                                    <span class="fw-semibold">{{ $bus->registration_number }}</span>
                                </div>
                            </td>
                            <td>{{ $bus->model }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $bus->total_seats }} lugares</span>
                            </td>
                            <td class="text-center">
                                @if($bus->is_active)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-warning">Inativo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="ph-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('buses.show', $bus) }}">
                                                <i class="ph-eye me-2"></i>Ver detalhes
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item edit-bus" href="#" 
                                               data-id="{{ $bus->id }}"
                                               data-registration="{{ $bus->registration_number }}"
                                               data-model="{{ $bus->model }}"
                                               data-seats="{{ $bus->total_seats }}"
                                               data-active="{{ $bus->is_active ? 1 : 0 }}">
                                                <i class="ph-pencil me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('buses.seat-configuration', $bus) }}">
                                                <i class="ph-chair me-2"></i>Configurar Assentos
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item toggle-status" href="#" data-id="{{ $bus->id }}">
                                                <i class="ph-{{ $bus->is_active ? 'x' : 'check' }}-circle me-2"></i>
                                                {{ $bus->is_active ? 'Desativar' : 'Ativar' }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-bus" href="#" data-id="{{ $bus->id }}">
                                                <i class="ph-trash me-2"></i>Excluir
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="ph-bus ph-3x d-block mb-3"></i>
                                <p class="mb-0">Nenhum autocarro cadastrado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($buses->hasPages())
            <div class="card-footer">
                {{ $buses->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Create Bus -->
    <div class="modal fade" id="createBusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ph-plus-circle me-2"></i>
                        Novo Autocarro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createBusForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Número de Matrícula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="registration_number" required placeholder="Ex: ABC-1234">
                            <small class="form-text text-muted">A matrícula será convertida automaticamente para maiúsculas</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Modelo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="model" required placeholder="Ex: Mercedes Sprinter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total de Lugares <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="total_seats" required min="1" max="100" placeholder="Ex: 45">
                            <small class="form-text text-muted">Mínimo: 1 lugar | Máximo: 100 lugares</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="createIsActive" checked>
                                <label class="form-check-label" for="createIsActive">Autocarro ativo</label>
                            </div>
                        </div>

                        <div class="alert alert-info border-0">
                            <i class="ph-info me-2"></i>
                            A configuração dos assentos será criada automaticamente. Você poderá personalizá-la depois.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-check me-2"></i>Criar Autocarro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Bus -->
    <div class="modal fade" id="editBusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ph-pencil me-2"></i>
                        Editar Autocarro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editBusForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editBusId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Número de Matrícula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="registration_number" id="editRegistrationNumber" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Modelo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="model" id="editModel" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total de Lugares <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="total_seats" id="editTotalSeats" required min="1" max="100">
                            <small class="form-text text-warning">
                                <i class="ph-warning me-1"></i>
                                Alterar o número de lugares reconfigurará os assentos
                            </small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive">
                                <label class="form-check-label" for="editIsActive">Autocarro ativo</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-check me-2"></i>Atualizar Autocarro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-uppercase registration number
    $('input[name="registration_number"]').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    // Create Bus Form Submit
    $('#createBusForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="ph-spinner ph-spin me-2"></i>Criando...');
        
        $.ajax({
            url: '{{ route("buses.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toast.success(response.message);
                    $('#createBusModal').modal('hide');
                    location.reload();
                } else {
                    toast.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = xhr.responseJSON.message || 'Erro de validação';
                    
                    if (errors) {
                        errorMessage += ':<br>';
                        Object.values(errors).forEach(error => {
                            errorMessage += `• ${error[0]}<br>`;
                        });
                    }
                    
                    toast.error(errorMessage);
                } else {
                    toast.error(xhr.responseJSON?.message || 'Erro ao criar autocarro');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Edit Bus Button Click
    $('.edit-bus').on('click', function(e) {
        e.preventDefault();
        
        const id = $(this).data('id');
        const registration = $(this).data('registration');
        const model = $(this).data('model');
        const seats = $(this).data('seats');
        const isActive = $(this).data('active');
        
        $('#editBusId').val(id);
        $('#editRegistrationNumber').val(registration);
        $('#editModel').val(model);
        $('#editTotalSeats').val(seats);
        $('#editIsActive').prop('checked', isActive == 1);
        
        $('#editBusModal').modal('show');
    });

    // Edit Bus Form Submit
    $('#editBusForm').on('submit', function(e) {
        e.preventDefault();
        
        const busId = $('#editBusId').val();
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="ph-spinner ph-spin me-2"></i>Atualizando...');
        
        $.ajax({
            url: `/buses/${busId}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toast.success(response.message);
                    $('#editBusModal').modal('hide');
                    location.reload();
                } else {
                    toast.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = xhr.responseJSON.message || 'Erro de validação';
                    
                    if (errors) {
                        errorMessage += ':<br>';
                        Object.values(errors).forEach(error => {
                            errorMessage += `• ${error[0]}<br>`;
                        });
                    }
                    
                    toast.error(errorMessage);
                } else {
                    toast.error(xhr.responseJSON?.message || 'Erro ao atualizar autocarro');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Toggle Status
    $('.toggle-status').on('click', function(e) {
        e.preventDefault();
        
        const busId = $(this).data('id');
        
        if (confirm('Tem certeza que deseja alterar o status deste autocarro?')) {
            $.ajax({
                url: `/buses/${busId}/toggle-status`,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toast.success(response.message);
                        location.reload();
                    } else {
                        toast.error(response.message);
                    }
                },
                error: function(xhr) {
                    toast.error(xhr.responseJSON?.message || 'Erro ao alterar status');
                }
            });
        }
    });

    // Delete Bus
    $('.delete-bus').on('click', function(e) {
        e.preventDefault();
        
        const busId = $(this).data('id');
        
        if (confirm('Tem certeza que deseja excluir este autocarro? Esta ação não pode ser desfeita.')) {
            $.ajax({
                url: `/buses/${busId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toast.success(response.message);
                        location.reload();
                    } else {
                        toast.error(response.message);
                    }
                },
                error: function(xhr) {
                    toast.error(xhr.responseJSON?.message || 'Erro ao excluir autocarro');
                }
            });
        }
    });

    // Reset Create Modal on close
    $('#createBusModal').on('hidden.bs.modal', function() {
        $('#createBusForm')[0].reset();
        $('#createIsActive').prop('checked', true);
    });
});
</script>
@endpush
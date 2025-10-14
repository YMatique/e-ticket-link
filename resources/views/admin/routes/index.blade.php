@extends('layouts.app')

@section('title', 'Rotas')

@section('breadcrumbs')
    <span class="breadcrumb-item active">Gestão de Viagens</span>
    <span class="breadcrumb-item active">Rotas</span>
@endsection

@section('page-title', 'Rotas')

@section('header-actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRouteModal">
        <i class="ph-plus me-2"></i>
        Nova Rota
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-3">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['total_routes'] }}</h4>
                        <span class="text-muted">Total de Rotas</span>
                    </div>
                    <i class="ph-map-trifold ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['active_routes'] }}</h4>
                        <span class="text-muted">Rotas Ativas</span>
                    </div>
                    <i class="ph-check-circle ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['inactive_routes'] }}</h4>
                        <span class="text-muted">Rotas Inativas</span>
                    </div>
                    <i class="ph-x-circle ph-2x text-danger opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ number_format($stats['total_distance'], 0, ',', '.') }} km</h4>
                        <span class="text-muted">Distância Total</span>
                    </div>
                    <i class="ph-navigation-arrow ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('routes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nome da cidade..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Província Origem</label>
                    <select name="origin_province_id" class="form-select" id="originProvinceFilter">
                        <option value="">Todas</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ request('origin_province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cidade Origem</label>
                    <select name="origin_city_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('origin_city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Província Destino</label>
                    <select name="destination_province_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ request('destination_province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Ativas</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inativas</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ph-funnel"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Routes Table -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Lista de Rotas</h5>
            <div class="ms-auto">
                <span class="badge bg-primary">{{ $routes->total() }} registros</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th style="width: 120px;">Distância</th>
                        <th style="width: 120px;">Duração</th>
                        <th style="width: 100px;" class="text-center">Status</th>
                        <th style="width: 150px;" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                        <tr>
                            <td class="fw-semibold">{{ $route->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $route->originCity->name }}</div>
                                <small class="text-muted">{{ $route->originCity->province->name }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $route->destinationCity->name }}</div>
                                <small class="text-muted">{{ $route->destinationCity->province->name }}</small>
                            </td>
                            <td>
                                @if($route->distance_km)
                                    <span class="badge bg-info">{{ number_format($route->distance_km, 0) }} km</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($route->estimated_duration_minutes)
                                    <span class="badge bg-secondary">{{ $route->durationInHours() }}h</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($route->is_active)
                                    <span class="badge bg-success">Ativa</span>
                                @else
                                    <span class="badge bg-danger">Inativa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="ph-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('routes.show', $route) }}">
                                                <i class="ph-eye me-2"></i>Ver detalhes
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item edit-route" href="#" 
                                               data-id="{{ $route->id }}"
                                               data-origin="{{ $route->origin_city_id }}"
                                               data-destination="{{ $route->destination_city_id }}"
                                               data-distance="{{ $route->distance_km }}"
                                               data-duration="{{ $route->estimated_duration_minutes }}"
                                               data-active="{{ $route->is_active ? 1 : 0 }}">
                                                <i class="ph-pencil me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item toggle-status" href="#" data-id="{{ $route->id }}">
                                                <i class="ph-{{ $route->is_active ? 'x' : 'check' }}-circle me-2"></i>
                                                {{ $route->is_active ? 'Desativar' : 'Ativar' }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-route" href="#" data-id="{{ $route->id }}">
                                                <i class="ph-trash me-2"></i>Excluir
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="ph-map-trifold ph-3x d-block mb-3"></i>
                                <p class="mb-0">Nenhuma rota cadastrada</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($routes->hasPages())
            <div class="card-footer">
                {{ $routes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Create Route -->
    <div class="modal fade" id="createRouteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ph-plus-circle me-2"></i>
                        Nova Rota
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createRouteForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Província Origem <span class="text-danger">*</span></label>
                            <select class="form-select" id="createOriginProvince" required>
                                <option value="">Selecione...</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cidade Origem <span class="text-danger">*</span></label>
                            <select class="form-select" name="origin_city_id" id="createOriginCity" required>
                                <option value="">Selecione a província primeiro...</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Província Destino <span class="text-danger">*</span></label>
                            <select class="form-select" id="createDestinationProvince" required>
                                <option value="">Selecione...</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cidade Destino <span class="text-danger">*</span></label>
                            <select class="form-select" name="destination_city_id" id="createDestinationCity" required>
                                <option value="">Selecione a província primeiro...</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Distância (km)</label>
                                    <input type="number" class="form-control" name="distance_km" step="0.01" min="0" placeholder="Ex: 350.5">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Duração (minutos)</label>
                                    <input type="number" class="form-control" name="estimated_duration_minutes" min="0" placeholder="Ex: 240">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="createIsActive" checked>
                                <label class="form-check-label" for="createIsActive">Rota ativa</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-check me-2"></i>Criar Rota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Route -->
    <div class="modal fade" id="editRouteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ph-pencil me-2"></i>
                        Editar Rota
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editRouteForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editRouteId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Cidade Origem <span class="text-danger">*</span></label>
                            <select class="form-select" name="origin_city_id" id="editOriginCity" required>
                                <option value="">Selecione...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }} ({{ $city->province->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cidade Destino <span class="text-danger">*</span></label>
                            <select class="form-select" name="destination_city_id" id="editDestinationCity" required>
                                <option value="">Selecione...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }} ({{ $city->province->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Distância (km)</label>
                                    <input type="number" class="form-control" name="distance_km" id="editDistance" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Duração (minutos)</label>
                                    <input type="number" class="form-control" name="estimated_duration_minutes" id="editDuration" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive">
                                <label class="form-check-label" for="editIsActive">Rota ativa</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-check me-2"></i>Atualizar Rota
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
    // Carregar cidades ao selecionar província (Create Modal)
    $('#createOriginProvince, #createDestinationProvince').on('change', function() {
        const provinceId = $(this).val();
        const isOrigin = $(this).attr('id') === 'createOriginProvince';
        const targetSelect = isOrigin ? '#createOriginCity' : '#createDestinationCity';
        
        if (provinceId) {
            loadCitiesByProvince(provinceId, targetSelect);
        } else {
            $(targetSelect).html('<option value="">Selecione a província primeiro...</option>');
        }
    });

    // Função para carregar cidades por província
    function loadCitiesByProvince(provinceId, targetSelect) {
        $.ajax({
            url: `/provinces/${provinceId}/cities`,
            method: 'GET',
            success: function(cities) {
                let options = '<option value="">Selecione...</option>';
                cities.forEach(city => {
                    options += `<option value="${city.id}">${city.name}</option>`;
                });
                $(targetSelect).html(options);
            },
            error: function() {
                toast.error('Erro ao carregar cidades');
            }
        });
    }

    // Create Route Form Submit
    $('#createRouteForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="ph-spinner ph-spin me-2"></i>Criando...');
        
        $.ajax({
            url: '{{ route("routes.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toast.success(response.message);
                    $('#createRouteModal').modal('hide');
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
                    toast.error(xhr.responseJSON?.message || 'Erro ao criar rota');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Edit Route Button Click
    $('.edit-route').on('click', function(e) {
        e.preventDefault();
        
        const id = $(this).data('id');
        const origin = $(this).data('origin');
        const destination = $(this).data('destination');
        const distance = $(this).data('distance');
        const duration = $(this).data('duration');
        const isActive = $(this).data('active');
        
        $('#editRouteId').val(id);
        $('#editOriginCity').val(origin);
        $('#editDestinationCity').val(destination);
        $('#editDistance').val(distance);
        $('#editDuration').val(duration);
        $('#editIsActive').prop('checked', isActive == 1);
        
        $('#editRouteModal').modal('show');
    });

    // Edit Route Form Submit
    $('#editRouteForm').on('submit', function(e) {
        e.preventDefault();
        
        const routeId = $('#editRouteId').val();
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="ph-spinner ph-spin me-2"></i>Atualizando...');
        
        $.ajax({
            url: `/routes/${routeId}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toast.success(response.message);
                    $('#editRouteModal').modal('hide');
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
                    toast.error(xhr.responseJSON?.message || 'Erro ao atualizar rota');
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
        
        const routeId = $(this).data('id');
        const btn = $(this);
        
        if (confirm('Tem certeza que deseja alterar o status desta rota?')) {
            $.ajax({
                url: `/routes/${routeId}/toggle-status`,
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

    // Delete Route
    $('.delete-route').on('click', function(e) {
        e.preventDefault();
        
        const routeId = $(this).data('id');
        
        if (confirm('Tem certeza que deseja excluir esta rota? Esta ação não pode ser desfeita.')) {
            $.ajax({
                url: `/routes/${routeId}`,
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
                    toast.error(xhr.responseJSON?.message || 'Erro ao excluir rota');
                }
            });
        }
    });

    // Reset Create Modal on close
    $('#createRouteModal').on('hidden.bs.modal', function() {
        $('#createRouteForm')[0].reset();
        $('#createOriginCity, #createDestinationCity').html('<option value="">Selecione a província primeiro...</option>');
    });
});
</script>
@endpush
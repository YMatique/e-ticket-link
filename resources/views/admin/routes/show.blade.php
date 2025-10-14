@extends('layouts.app')

@section('title', 'Detalhes da Rota')

@section('breadcrumbs')
    <span class="breadcrumb-item">Gestão de Viagens</span>
    <a href="{{ route('routes.index') }}" class="breadcrumb-item">Rotas</a>
    <span class="breadcrumb-item active">Detalhes</span>
@endsection

@section('page-title')
    <i class="ph-map-trifold me-2"></i>
    {{ $route->name() }}
@endsection

@section('header-actions')
    <a href="{{ route('routes.index') }}" class="btn btn-light">
        <i class="ph-arrow-left me-2"></i>
        Voltar
    </a>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-3">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['total_schedules'] }}</h3>
                        <span class="text-muted">Total de Horários</span>
                    </div>
                    <i class="ph-calendar ph-3x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['active_schedules'] }}</h3>
                        <span class="text-muted">Viagens Agendadas</span>
                    </div>
                    <i class="ph-clock ph-3x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['completed_schedules'] }}</h3>
                        <span class="text-muted">Viagens Concluídas</span>
                    </div>
                    <i class="ph-check-circle ph-3x text-info opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['total_tickets'] }}</h3>
                        <span class="text-muted">Bilhetes Vendidos</span>
                    </div>
                    <i class="ph-ticket ph-3x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações da Rota -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-info me-2"></i>
                        Informações da Rota
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 40%;">ID:</td>
                            <td class="fw-semibold">{{ $route->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nome da Rota:</td>
                            <td class="fw-semibold">{{ $route->name() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nome Completo:</td>
                            <td class="fw-semibold">{{ $route->fullName() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                @if($route->is_active)
                                    <span class="badge bg-success">Ativa</span>
                                @else
                                    <span class="badge bg-danger">Inativa</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Distância:</td>
                            <td>
                                @if($route->distance_km)
                                    <span class="badge bg-info">{{ number_format($route->distance_km, 1) }} km</span>
                                @else
                                    <span class="text-muted">Não informada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Duração Estimada:</td>
                            <td>
                                @if($route->estimated_duration_minutes)
                                    <span class="badge bg-secondary">
                                        {{ $route->durationInHours() }} horas ({{ $route->estimated_duration_minutes }} min)
                                    </span>
                                @else
                                    <span class="text-muted">Não informada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Criado em:</td>
                            <td>{{ $route->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Atualizado em:</td>
                            <td>{{ $route->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cidades -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-map-pin me-2"></i>
                        Cidades da Rota
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Origem -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="ph-map-pin text-success me-2"></i>
                            ORIGEM
                        </h6>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="flex-fill">
                                <div class="fw-semibold fs-5">{{ $route->originCity->name }}</div>
                                <div class="text-muted">{{ $route->originCity->province->name }}</div>
                            </div>
                            <a href="{{ route('cities.show', $route->originCity) }}" class="btn btn-sm btn-light">
                                <i class="ph-eye"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Seta -->
                    <div class="text-center mb-4">
                        <i class="ph-arrow-down ph-2x text-primary"></i>
                    </div>

                    <!-- Destino -->
                    <div>
                        <h6 class="text-muted mb-3">
                            <i class="ph-flag text-danger me-2"></i>
                            DESTINO
                        </h6>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="flex-fill">
                                <div class="fw-semibold fs-5">{{ $route->destinationCity->name }}</div>
                                <div class="text-muted">{{ $route->destinationCity->province->name }}</div>
                            </div>
                            <a href="{{ route('cities.show', $route->destinationCity) }}" class="btn btn-sm btn-light">
                                <i class="ph-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ph-lightning me-2"></i>
                        Ações Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('schedules.create') }}?route_id={{ $route->id }}" class="btn btn-primary">
                            <i class="ph-plus me-2"></i>
                            Criar Horário/Viagem
                        </a>
                        <button type="button" class="btn btn-outline-primary toggle-status" data-id="{{ $route->id }}">
                            <i class="ph-{{ $route->is_active ? 'x' : 'check' }}-circle me-2"></i>
                            {{ $route->is_active ? 'Desativar Rota' : 'Ativar Rota' }}
                        </button>
                        <button type="button" class="btn btn-outline-danger delete-route" data-id="{{ $route->id }}">
                            <i class="ph-trash me-2"></i>
                            Excluir Rota
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Horários/Viagens -->
    @if($route->schedules->isNotEmpty())
        <div class="card mt-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">
                    <i class="ph-calendar me-2"></i>
                    Horários e Viagens
                </h5>
                <div class="ms-auto">
                    <span class="badge bg-primary">{{ $route->schedules->count() }} horários</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Data/Hora Partida</th>
                            <th>Autocarro</th>
                            <th>Preço</th>
                            <th>Lugares</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($route->schedules as $schedule)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $schedule->departure_date->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $schedule->departure_time }}</small>
                                </td>
                                <td>
                                    <div>{{ $schedule->bus->model ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $schedule->bus->registration_number ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ number_format($schedule->price ?? 0, 2) }} MT</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $schedule->tickets_count ?? 0 }}/{{ $schedule->bus->total_seats ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'primary',
                                            'boarding' => 'warning',
                                            'departed' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'scheduled' => 'Agendado',
                                            'boarding' => 'Embarcando',
                                            'departed' => 'Em Viagem',
                                            'completed' => 'Concluído',
                                            'cancelled' => 'Cancelado',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$schedule->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$schedule->status] ?? $schedule->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-light">
                                        <i class="ph-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card mt-3">
            <div class="card-body text-center py-5">
                <i class="ph-calendar-x ph-4x text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Nenhum horário cadastrado</h5>
                <p class="text-muted mb-4">Esta rota ainda não possui horários de viagem cadastrados.</p>
                <a href="{{ route('schedules.create') }}?route_id={{ $route->id }}" class="btn btn-primary">
                    <i class="ph-plus me-2"></i>
                    Criar Primeiro Horário
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').on('click', function(e) {
        e.preventDefault();
        
        const routeId = $(this).data('id');
        
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
        
        if (confirm('Tem certeza que deseja excluir esta rota? Esta ação não pode ser desfeita e todos os horários serão perdidos.')) {
            $.ajax({
                url: `/routes/${routeId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toast.success(response.message);
                        window.location.href = '{{ route("routes.index") }}';
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
});
</script>
@endpush
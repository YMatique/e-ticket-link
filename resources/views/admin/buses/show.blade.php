@extends('layouts.app')

@section('title', 'Detalhes do Autocarro')

@section('breadcrumbs')
    <span class="breadcrumb-item">Gestão de Viagens</span>
    <a href="{{ route('buses.index') }}" class="breadcrumb-item">Autocarros</a>
    <span class="breadcrumb-item active">Detalhes</span>
@endsection

@section('page-title')
    <i class="ph-bus me-2"></i>
    {{ $bus->displayName() }}
@endsection

@section('header-actions')
    <a href="{{ route('buses.index') }}" class="btn btn-light">
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
                        <h3 class="mb-0">{{ $stats['total_trips'] }}</h3>
                        <span class="text-muted">Viagens Realizadas</span>
                    </div>
                    <i class="ph-check-circle ph-3x text-info opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $bus->total_seats }}</h3>
                        <span class="text-muted">Lugares Disponíveis</span>
                    </div>
                    <i class="ph-users ph-3x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações do Autocarro -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-info me-2"></i>
                        Informações do Autocarro
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 40%;">ID:</td>
                            <td class="fw-semibold">{{ $bus->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Matrícula:</td>
                            <td>
                                <span class="badge bg-dark fs-6">{{ $bus->registration_number }}</span>
                            </td>
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
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                @if($bus->is_active)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-warning">Inativo / Manutenção</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Configuração de Assentos:</td>
                            <td>
                                @if($bus->seat_configuration)
                                    <span class="badge bg-success">Configurado</span>
                                @else
                                    <span class="badge bg-secondary">Padrão</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Criado em:</td>
                            <td>{{ $bus->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Atualizado em:</td>
                            <td>{{ $bus->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
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
                        <a href="{{ route('schedules.create') }}?bus_id={{ $bus->id }}" class="btn btn-primary">
                            <i class="ph-plus me-2"></i>
                            Criar Horário/Viagem
                        </a>
                        <a href="{{ route('buses.seat-configuration', $bus) }}" class="btn btn-outline-info">
                            <i class="ph-chair me-2"></i>
                            Configurar Assentos
                        </a>
                        <button type="button" class="btn btn-outline-primary toggle-status" data-id="{{ $bus->id }}">
                            <i class="ph-{{ $bus->is_active ? 'x' : 'check' }}-circle me-2"></i>
                            {{ $bus->is_active ? 'Colocar em Manutenção' : 'Reativar Autocarro' }}
                        </button>
                        <button type="button" class="btn btn-outline-danger delete-bus" data-id="{{ $bus->id }}">
                            <i class="ph-trash me-2"></i>
                            Excluir Autocarro
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa de Assentos -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-chair me-2"></i>
                        Mapa de Assentos
                    </h5>
                    <a href="{{ route('buses.seat-configuration', $bus) }}" class="btn btn-sm btn-light ms-auto">
                        <i class="ph-pencil me-1"></i>
                        Editar
                    </a>
                </div>
                <div class="card-body">
                    @if($bus->seat_configuration)
                        <div class="text-center mb-3">
                            <div class="badge bg-dark mb-2">MOTORISTA</div>
                        </div>
                        
                        <div class="seat-map">
                            @foreach($bus->seat_configuration as $rowKey => $rowSeats)
                                <div class="seat-row mb-2 d-flex justify-content-center gap-2">
                                    @foreach($rowSeats as $seat)
                                        <div class="seat-item text-center">
                                            <div class="badge bg-primary" style="width: 45px;">
                                                {{ $seat['number'] }}
                                            </div>
                                        </div>
                                        @if(isset($seat['position']) && $seat['position'] == 'left' && !$loop->last)
                                            <div style="width: 30px;"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-info border-0 mt-3">
                            <i class="ph-info me-2"></i>
                            Total: <strong>{{ $bus->total_seats }} assentos</strong> configurados
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="ph-chair ph-3x mb-3 d-block"></i>
                            <p>Configuração de assentos não disponível</p>
                            <a href="{{ route('buses.seat-configuration', $bus) }}" class="btn btn-sm btn-primary">
                                Configurar Agora
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Horários/Viagens -->
    @if($bus->schedules->isNotEmpty())
        <div class="card mt-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">
                    <i class="ph-calendar me-2"></i>
                    Horários e Viagens
                </h5>
                <div class="ms-auto">
                    <span class="badge bg-primary">{{ $bus->schedules->count() }} horários</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Rota</th>
                            <th>Data/Hora Partida</th>
                            <th>Preço</th>
                            <th>Lugares</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bus->schedules->take(10) as $schedule)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $schedule->route->originCity->province->name }} - {{ $schedule->route->destinationCity->province->name }}
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $schedule->departure_date->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $schedule->departure_time }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ number_format($schedule->price ?? 0, 2) }} MT</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $schedule->tickets_count ?? 0 }}/{{ $bus->total_seats }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColors = [
                                            'active' => 'primary',
                                            'full' => 'warning',
                                            'departed' => 'info',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'active' => 'Ativo',
                                            'full' => 'Lotado',
                                            'departed' => 'Partiu',
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
            @if($bus->schedules->count() > 10)
                <div class="card-footer text-center">
                    <a href="{{ route('schedules.index') }}?bus_id={{ $bus->id }}" class="fw-semibold">
                        Ver todos os horários ({{ $bus->schedules->count() }})
                        <i class="ph-arrow-circle-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="card mt-3">
            <div class="card-body text-center py-5">
                <i class="ph-calendar-x ph-4x text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Nenhum horário cadastrado</h5>
                <p class="text-muted mb-4">Este autocarro ainda não possui horários de viagem cadastrados.</p>
                <a href="{{ route('schedules.create') }}?bus_id={{ $bus->id }}" class="btn btn-primary">
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
        
        if (confirm('Tem certeza que deseja excluir este autocarro? Esta ação não pode ser desfeita e todos os horários serão perdidos.')) {
            $.ajax({
                url: `/buses/${busId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toast.success(response.message);
                        window.location.href = '{{ route("buses.index") }}';
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
});
</script>
@endpush
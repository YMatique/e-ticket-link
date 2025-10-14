@extends('layouts.app')

@section('title', 'Horários de Viagem')

@section('page-title')
	Horários - <span class="fw-normal">Gestão de Viagens</span>
@endsection

@section('breadcrumbs')
	<a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
	<span class="breadcrumb-item active">Horários</span>
@endsection

@section('header-actions')
	<a href="{{ route('schedules.create') }}" class="btn btn-primary">
		<i class="ph-plus me-2"></i>
		Novo Horário
	</a>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="row mb-3">
	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ $stats['total'] }}</h4>
					<span class="text-muted">Total de Horários</span>
				</div>
				<div class="ms-3">
					<i class="ph-calendar-blank ph-3x text-primary opacity-75"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ $stats['today'] }}</h4>
					<span class="text-muted">Partidas Hoje</span>
				</div>
				<div class="ms-3">
					<i class="ph-rocket-launch ph-3x text-success opacity-75"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ $stats['upcoming'] }}</h4>
					<span class="text-muted">Próximas Viagens</span>
				</div>
				<div class="ms-3">
					<i class="ph-clock ph-3x text-info opacity-75"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ $stats['departed'] }}</h4>
					<span class="text-muted">Viagens Realizadas</span>
				</div>
				<div class="ms-3">
					<i class="ph-check-circle ph-3x text-secondary opacity-75"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Filters -->
<div class="card">
	<div class="card-header">
		<h5 class="mb-0">
			<i class="ph-funnel me-2"></i>
			Filtros
		</h5>
	</div>
	<div class="card-body">
		<form action="{{ route('schedules.index') }}" method="GET">
			<div class="row">
				<div class="col-md-3">
					<div class="mb-3">
						<label class="form-label">Pesquisar</label>
						<input type="text" name="search" class="form-control" 
							   placeholder="Cidade origem/destino..." 
							   value="{{ request('search') }}">
					</div>
				</div>

				<div class="col-md-3">
					<div class="mb-3">
						<label class="form-label">Rota</label>
						<select name="route_id" class="form-select">
							<option value="">Todas as rotas</option>
							@foreach($routes as $route)
								<option value="{{ $route->id }}" 
										{{ request('route_id') == $route->id ? 'selected' : '' }}>
									{{ $route->originCity->name }} → {{ $route->destinationCity->name }}
								</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="mb-3">
						<label class="form-label">Autocarro</label>
						<select name="bus_id" class="form-select">
							<option value="">Todos</option>
							@foreach($buses as $bus)
								<option value="{{ $bus->id }}" 
										{{ request('bus_id') == $bus->id ? 'selected' : '' }}>
									{{ $bus->registration_number }}
								</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="mb-3">
						<label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="">Todos</option>
							<option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
							<option value="full" {{ request('status') == 'full' ? 'selected' : '' }}>Lotado</option>
							<option value="departed" {{ request('status') == 'departed' ? 'selected' : '' }}>Partiu</option>
							<option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="mb-3">
						<label class="form-label">Data Inicial</label>
						<input type="date" name="date_from" class="form-control" 
							   value="{{ request('date_from') }}">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					<div class="mb-3">
						<label class="form-label">Data Final</label>
						<input type="date" name="date_to" class="form-control" 
							   value="{{ request('date_to') }}">
					</div>
				</div>

				<div class="col-md-10 text-end">
					<label class="form-label d-block">&nbsp;</label>
					<button type="submit" class="btn btn-primary">
						<i class="ph-magnifying-glass me-2"></i>
						Filtrar
					</button>
					<a href="{{ route('schedules.index') }}" class="btn btn-light">
						<i class="ph-x me-2"></i>
						Limpar
					</a>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Schedules Table -->
<div class="card mt-3">
	<div class="card-header d-flex align-items-center">
		<h5 class="mb-0">Lista de Horários</h5>
		<span class="ms-auto text-muted">{{ $schedules->total() }} horários encontrados</span>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Rota</th>
					<th>Data/Hora Partida</th>
					<th>Autocarro</th>
					<th>Preço</th>
					<th>Lugares</th>
					<th>Ocupação</th>
					<th class="text-center">Status</th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>
			<tbody>
				@forelse($schedules as $schedule)
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<i class="ph-map-pin text-primary me-2"></i>
								<div>
									<div class="fw-semibold">
										{{ $schedule->route->originCity->name }} 
										<i class="ph-arrow-right mx-1"></i> 
										{{ $schedule->route->destinationCity->name }}
									</div>
									<small class="text-muted">
										{{ number_format($schedule->route->distance, 0) }} km
									</small>
								</div>
							</div>
						</td>
						<td>
							<div class="fw-semibold">{{ $schedule->departure_date->format('d/m/Y') }}</div>
							<small class="text-muted">
								<i class="ph-clock me-1"></i>
								{{ $schedule->departure_time }}
							</small>
						</td>
						<td>
							<div>{{ $schedule->bus->model }}</div>
							<small class="text-muted">{{ $schedule->bus->registration_number }}</small>
						</td>
						<td>
							<span class="badge bg-success bg-opacity-10 text-success">
								{{ number_format($schedule->price, 2) }} MT
							</span>
						</td>
						<td>
							@php
								$totalSeats = $schedule->bus->total_seats;
								$bookedSeats = $schedule->tickets_count ?? 0;
								$availableSeats = $totalSeats - $bookedSeats;
							@endphp
							<div class="fw-semibold">{{ $bookedSeats }}/{{ $totalSeats }}</div>
							<small class="text-muted">{{ $availableSeats }} disponíveis</small>
						</td>
						<td>
							@php
								$occupancy = $totalSeats > 0 ? ($bookedSeats / $totalSeats) * 100 : 0;
								$progressColor = $occupancy >= 80 ? 'danger' : ($occupancy >= 50 ? 'warning' : 'success');
							@endphp
							<div class="progress" style="height: 8px;">
								<div class="progress-bar bg-{{ $progressColor }}" 
									 style="width: {{ $occupancy }}%">
								</div>
							</div>
							<small class="text-muted">{{ number_format($occupancy, 0) }}%</small>
						</td>
						<td class="text-center">
							@php
								$statusConfig = [
									'active' => ['class' => 'success', 'icon' => 'check-circle', 'label' => 'Activo'],
									'full' => ['class' => 'warning', 'icon' => 'warning-circle', 'label' => 'Lotado'],
									'departed' => ['class' => 'info', 'icon' => 'airplane-takeoff', 'label' => 'Partiu'],
									'cancelled' => ['class' => 'danger', 'icon' => 'x-circle', 'label' => 'Cancelado'],
								];
								$status = $statusConfig[$schedule->status] ?? $statusConfig['active'];
							@endphp
							<span class="badge bg-{{ $status['class'] }} bg-opacity-10 text-{{ $status['class'] }}">
								<i class="ph-{{ $status['icon'] }} me-1"></i>
								{{ $status['label'] }}
							</span>
						</td>
						<td class="text-center">
							<div class="dropdown">
								<a href="#" class="text-body" data-bs-toggle="dropdown">
									<i class="ph-dots-three-vertical"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="{{ route('schedules.show', $schedule) }}" class="dropdown-item">
										<i class="ph-eye me-2"></i>
										Ver Detalhes
									</a>
									<a href="{{ route('schedules.seats', $schedule) }}" class="dropdown-item">
										<i class="ph-armchair me-2"></i>
										Ver Assentos
									</a>
									@if($schedule->status !== 'departed')
										<a href="{{ route('schedules.edit', $schedule) }}" class="dropdown-item">
											<i class="ph-pencil me-2"></i>
											Editar
										</a>
									@endif
									@if($schedule->status === 'active')
										<div class="dropdown-divider"></div>
										<form action="{{ route('schedules.cancel', $schedule) }}" 
											  method="POST" class="d-inline">
											@csrf
											@method('PATCH')
											<button type="submit" class="dropdown-item text-warning"
													onclick="return confirm('Cancelar este horário?')">
												<i class="ph-x-circle me-2"></i>
												Cancelar Horário
											</button>
										</form>
									@endif
									@if($schedule->status !== 'departed' && $bookedSeats === 0)
										<div class="dropdown-divider"></div>
										<form action="{{ route('schedules.destroy', $schedule) }}" 
											  method="POST" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="dropdown-item text-danger"
													onclick="return confirm('Excluir este horário permanentemente?')">
												<i class="ph-trash me-2"></i>
												Excluir
											</button>
										</form>
									@endif
								</div>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="8" class="text-center text-muted py-5">
							<i class="ph-calendar-x ph-3x mb-3 d-block"></i>
							<p class="mb-0">Nenhum horário encontrado</p>
							<a href="{{ route('schedules.create') }}" class="btn btn-primary mt-3">
								<i class="ph-plus me-2"></i>
								Criar Primeiro Horário
							</a>
						</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	@if($schedules->hasPages())
		<div class="card-footer">
			{{ $schedules->links() }}
		</div>
	@endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Auto-submit form on select change
	const selects = document.querySelectorAll('select[name="route_id"], select[name="bus_id"], select[name="status"]');
	selects.forEach(select => {
		select.addEventListener('change', function() {
			this.closest('form').submit();
		});
	});
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Detalhes do Horário')

@section('page-title')
	Horários - <span class="fw-normal">Detalhes</span>
@endsection

@section('breadcrumbs')
	<a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
	<a href="{{ route('schedules.index') }}" class="breadcrumb-item">Horários</a>
	<span class="breadcrumb-item active">Detalhes</span>
@endsection

@section('header-actions')
	<div class="d-flex gap-2">
		<a href="{{ route('schedules.seats', $schedule) }}" class="btn btn-info">
			<i class="ph-armchair me-2"></i>
			Ver Assentos
		</a>
		@if($schedule->status !== 'departed')
			<a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-primary">
				<i class="ph-pencil me-2"></i>
				Editar
			</a>
		@endif
	</div>
@endsection

@section('content')

<!-- Status Alert -->
@if($schedule->status === 'cancelled')
	<div class="alert alert-danger alert-dismissible fade show">
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		<div class="d-flex align-items-center">
			<i class="ph-x-circle ph-2x me-3"></i>
			<div>
				<h6 class="mb-1">Horário Cancelado</h6>
				<p class="mb-0">Este horário foi cancelado e não está mais disponível para venda de bilhetes.</p>
			</div>
		</div>
	</div>
@elseif($schedule->status === 'full')
	<div class="alert alert-warning alert-dismissible fade show">
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		<div class="d-flex align-items-center">
			<i class="ph-warning-circle ph-2x me-3"></i>
			<div>
				<h6 class="mb-1">Horário Lotado</h6>
				<p class="mb-0">Todos os lugares estão vendidos. Não há mais assentos disponíveis.</p>
			</div>
		</div>
	</div>
@elseif($schedule->status === 'departed')
	<div class="alert alert-info alert-dismissible fade show">
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		<div class="d-flex align-items-center">
			<i class="ph-airplane-takeoff ph-2x me-3"></i>
			<div>
				<h6 class="mb-1">Viagem Concluída</h6>
				<p class="mb-0">Este autocarro já partiu. Consulte os bilhetes vendidos abaixo.</p>
			</div>
		</div>
	</div>
@endif

<!-- Statistics Cards -->
<div class="row mb-3">
	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ $stats['booked_seats'] }}</h4>
					<span class="text-muted">Lugares Vendidos</span>
				</div>
				<div class="ms-3">
					<i class="ph-ticket ph-3x text-success opacity-75"></i>
				</div>
			</div>
			<div class="progress mt-2" style="height: 4px;">
				<div class="progress-bar bg-success" 
					 style="width: {{ $stats['occupancy_rate'] }}%"></div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ $stats['available_seats'] }}</h4>
					<span class="text-muted">Lugares Disponíveis</span>
				</div>
				<div class="ms-3">
					<i class="ph-armchair ph-3x text-primary opacity-75"></i>
				</div>
			</div>
			<small class="text-muted mt-2">
				{{ $stats['total_seats'] }} lugares no total
			</small>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ number_format($stats['occupancy_rate'], 1) }}%</h4>
					<span class="text-muted">Taxa de Ocupação</span>
				</div>
				<div class="ms-3">
					<i class="ph-chart-line ph-3x text-info opacity-75"></i>
				</div>
			</div>
			<div class="progress mt-2" style="height: 4px;">
				@php
					$progressColor = $stats['occupancy_rate'] >= 80 ? 'danger' : 
									($stats['occupancy_rate'] >= 50 ? 'warning' : 'info');
				@endphp
				<div class="progress-bar bg-{{ $progressColor }}" 
					 style="width: {{ $stats['occupancy_rate'] }}%"></div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ number_format($stats['revenue'], 2) }} MT</h4>
					<span class="text-muted">Receita Actual</span>
				</div>
				<div class="ms-3">
					<i class="ph-currency-circle-dollar ph-3x text-success opacity-75"></i>
				</div>
			</div>
			<small class="text-muted mt-2">
				Potencial: {{ number_format($stats['expected_revenue'], 2) }} MT
			</small>
		</div>
	</div>
</div>

<div class="row">
	<!-- Informações do Horário -->
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="ph-info me-2"></i>
					Informações do Horário
				</h5>
			</div>
			<div class="card-body">
				<div class="row mb-4">
					<div class="col-md-6">
						<h6 class="text-muted mb-2">
							<i class="ph-map-pin me-2"></i>
							Rota
						</h6>
						<div class="d-flex align-items-center mb-3">
							<div class="flex-fill">
								<div class="fw-bold fs-5">
									{{ $schedule->route->originCity->name }}
								</div>
								<small class="text-muted">Origem</small>
							</div>
							<i class="ph-arrow-right ph-2x text-primary mx-3"></i>
							<div class="flex-fill text-end">
								<div class="fw-bold fs-5">
									{{ $schedule->route->destinationCity->name }}
								</div>
								<small class="text-muted">Destino</small>
							</div>
						</div>
						<div class="row text-sm">
							<div class="col-6">
								<i class="ph-road-horizon me-1"></i>
								<span class="fw-semibold">{{ number_format($schedule->route->distance, 0) }} km</span>
							</div>
							<div class="col-6">
								<i class="ph-clock me-1"></i>
								<span class="fw-semibold">{{ $schedule->route->estimated_duration }}</span>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<h6 class="text-muted mb-2">
							<i class="ph-calendar me-2"></i>
							Data e Hora
						</h6>
						<div class="mb-3">
							<div class="fw-bold fs-4">
								{{ $schedule->departure_date->format('d/m/Y') }}
							</div>
							<div class="text-muted">
								<i class="ph-clock me-1"></i>
								Partida às {{ $schedule->departure_time }}
							</div>
						</div>
						@if($schedule->departure_date->isFuture())
							<div class="badge bg-info bg-opacity-10 text-info">
								<i class="ph-hourglass me-1"></i>
								Faltam {{ $schedule->departure_date->diffForHumans() }}
							</div>
						@elseif($schedule->departure_date->isToday())
							<div class="badge bg-success bg-opacity-10 text-success">
								<i class="ph-check-circle me-1"></i>
								Partida hoje
							</div>
						@else
							<div class="badge bg-secondary bg-opacity-10 text-secondary">
								<i class="ph-clock-countdown me-1"></i>
								{{ $schedule->departure_date->diffForHumans() }}
							</div>
						@endif
					</div>
				</div>

				<hr>

				<div class="row">
					<div class="col-md-6 mb-3">
						<h6 class="text-muted mb-2">
							<i class="ph-bus me-2"></i>
							Autocarro
						</h6>
						<div>
							<div class="fw-semibold">{{ $schedule->bus->model }}</div>
							<small class="text-muted">{{ $schedule->bus->registration_number }}</small>
						</div>
						<div class="mt-2">
							<span class="badge bg-primary bg-opacity-10 text-primary">
								<i class="ph-armchair me-1"></i>
								{{ $schedule->bus->total_seats }} lugares
							</span>
						</div>
					</div>

					<div class="col-md-6 mb-3">
						<h6 class="text-muted mb-2">
							<i class="ph-currency-circle-dollar me-2"></i>
							Preço
						</h6>
						<div>
							<div class="fw-bold fs-3 text-success">
								{{ number_format($schedule->price, 2) }} MT
							</div>
							<small class="text-muted">Por passageiro</small>
						</div>
					</div>

					<div class="col-md-6 mb-3">
						<h6 class="text-muted mb-2">
							<i class="ph-tag me-2"></i>
							Status
						</h6>
						@php
							$statusConfig = [
								'active' => ['class' => 'success', 'icon' => 'check-circle', 'label' => 'Activo'],
								'full' => ['class' => 'warning', 'icon' => 'warning-circle', 'label' => 'Lotado'],
								'departed' => ['class' => 'info', 'icon' => 'airplane-takeoff', 'label' => 'Partiu'],
								'cancelled' => ['class' => 'danger', 'icon' => 'x-circle', 'label' => 'Cancelado'],
							];
							$status = $statusConfig[$schedule->status] ?? $statusConfig['active'];
						@endphp
						<span class="badge bg-{{ $status['class'] }} bg-opacity-10 text-{{ $status['class'] }} fs-6">
							<i class="ph-{{ $status['icon'] }} me-1"></i>
							{{ $status['label'] }}
						</span>
					</div>

					<div class="col-md-6 mb-3">
						<h6 class="text-muted mb-2">
							<i class="ph-user me-2"></i>
							Criado por
						</h6>
						<div>
							<div class="fw-semibold">{{ $schedule->createdBy->name ?? 'Sistema' }}</div>
							<small class="text-muted">{{ $schedule->created_at->format('d/m/Y H:i') }}</small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Lista de Bilhetes -->
		<div class="card mt-3">
			<div class="card-header d-flex align-items-center">
				<h5 class="mb-0">
					<i class="ph-ticket me-2"></i>
					Bilhetes Vendidos
				</h5>
				<span class="ms-auto badge bg-primary">{{ $schedule->tickets->count() }} bilhetes</span>
			</div>

			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Nº Bilhete</th>
							<th>Passageiro</th>
							<th>Assento</th>
							<th>Preço</th>
							<th class="text-center">Status</th>
							<th class="text-center">Pagamento</th>
							<th>Data</th>
						</tr>
					</thead>
					<tbody>
						@forelse($schedule->tickets as $ticket)
							<tr>
								<td>
									<a href="{{ route('tickets.show', $ticket) }}" class="fw-semibold">
										{{ $ticket->ticket_number }}
									</a>
								</td>
								<td>
									<div>
										<div class="fw-semibold">{{ $ticket->passenger->name }}</div>
										<small class="text-muted">{{ $ticket->passenger->phone }}</small>
									</div>
								</td>
								<td>
									<span class="badge bg-secondary">
										<i class="ph-armchair me-1"></i>
										{{ $ticket->seat_number }}
									</span>
								</td>
								<td>
									<span class="fw-semibold">{{ number_format($ticket->price, 2) }} MT</span>
								</td>
								<td class="text-center">
									@php
										$ticketStatus = [
											'reserved' => ['class' => 'warning', 'icon' => 'clock', 'label' => 'Reservado'],
											'paid' => ['class' => 'success', 'icon' => 'check-circle', 'label' => 'Pago'],
											'validated' => ['class' => 'info', 'icon' => 'seal-check', 'label' => 'Validado'],
											'cancelled' => ['class' => 'danger', 'icon' => 'x-circle', 'label' => 'Cancelado'],
										];
										$tStatus = $ticketStatus[$ticket->status] ?? $ticketStatus['reserved'];
									@endphp
									<span class="badge bg-{{ $tStatus['class'] }} bg-opacity-10 text-{{ $tStatus['class'] }}">
										<i class="ph-{{ $tStatus['icon'] }} me-1"></i>
										{{ $tStatus['label'] }}
									</span>
								</td>
								<td class="text-center">
									@if($ticket->payment)
										<span class="badge bg-success bg-opacity-10 text-success">
											<i class="ph-check me-1"></i>
											{{ $ticket->payment->payment_method }}
										</span>
									@else
										<span class="badge bg-secondary">
											<i class="ph-minus me-1"></i>
											Pendente
										</span>
									@endif
								</td>
								<td>
									<div>{{ $ticket->created_at->format('d/m/Y') }}</div>
									<small class="text-muted">{{ $ticket->created_at->format('H:i') }}</small>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center text-muted py-4">
									<i class="ph-ticket ph-3x mb-2 d-block opacity-50"></i>
									<p class="mb-0">Nenhum bilhete vendido ainda</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Sidebar de Ações -->
	<div class="col-lg-4">
		<!-- Ações Rápidas -->
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="ph-lightning me-2"></i>
					Ações Rápidas
				</h6>
			</div>
			<div class="card-body">
				<div class="d-grid gap-2">
					@if($schedule->status === 'active')
						<a href="{{ route('tickets.create', ['schedule_id' => $schedule->id]) }}" 
						   class="btn btn-primary">
							<i class="ph-ticket me-2"></i>
							Vender Bilhete
						</a>

						<a href="{{ route('schedules.seats', $schedule) }}" 
						   class="btn btn-outline-info">
							<i class="ph-armchair me-2"></i>
							Ver Mapa de Assentos
						</a>

						<hr>

						<form action="{{ route('schedules.cancel', $schedule) }}" method="POST"
							  onsubmit="return confirm('Tem certeza que deseja cancelar este horário? Todos os passageiros serão notificados.')">
							@csrf
							@method('PATCH')
							<button type="submit" class="btn btn-outline-warning w-100">
								<i class="ph-x-circle me-2"></i>
								Cancelar Horário
							</button>
						</form>
					@endif

					@if($schedule->status !== 'departed')
						<a href="{{ route('schedules.edit', $schedule) }}" 
						   class="btn btn-outline-primary">
							<i class="ph-pencil me-2"></i>
							Editar Horário
						</a>
					@endif

					@if($schedule->status !== 'departed' && $schedule->tickets()->whereIn('status', ['paid', 'validated'])->count() === 0)
						<form action="{{ route('schedules.destroy', $schedule) }}" method="POST"
							  onsubmit="return confirm('Tem certeza que deseja excluir este horário permanentemente?')">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-outline-danger w-100">
								<i class="ph-trash me-2"></i>
								Excluir Horário
							</button>
						</form>
					@endif
				</div>
			</div>
		</div>

		<!-- Informações Adicionais -->
		<div class="card mt-3">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="ph-chart-bar me-2"></i>
					Estatísticas Detalhadas
				</h6>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<div class="d-flex justify-content-between mb-1">
						<small class="text-muted">Taxa de Ocupação</small>
						<small class="fw-semibold">{{ number_format($stats['occupancy_rate'], 1) }}%</small>
					</div>
					<div class="progress" style="height: 8px;">
						@php
							$color = $stats['occupancy_rate'] >= 80 ? 'danger' : 
									($stats['occupancy_rate'] >= 50 ? 'warning' : 'success');
						@endphp
						<div class="progress-bar bg-{{ $color }}" 
							 style="width: {{ $stats['occupancy_rate'] }}%"></div>
					</div>
				</div>

				<hr>

				<div class="d-flex justify-content-between mb-2">
					<span class="text-muted">
						<i class="ph-ticket me-1"></i>
						Bilhetes Vendidos
					</span>
					<span class="fw-semibold">{{ $stats['booked_seats'] }}</span>
				</div>

				<div class="d-flex justify-content-between mb-2">
					<span class="text-muted">
						<i class="ph-armchair me-1"></i>
						Lugares Disponíveis
					</span>
					<span class="fw-semibold">{{ $stats['available_seats'] }}</span>
				</div>

				<div class="d-flex justify-content-between mb-2">
					<span class="text-muted">
						<i class="ph-currency-circle-dollar me-1"></i>
						Receita Actual
					</span>
					<span class="fw-semibold text-success">{{ number_format($stats['revenue'], 2) }} MT</span>
				</div>

				<div class="d-flex justify-content-between mb-2">
					<span class="text-muted">
						<i class="ph-chart-line-up me-1"></i>
						Receita Potencial
					</span>
					<span class="fw-semibold">{{ number_format($stats['expected_revenue'], 2) }} MT</span>
				</div>

				<hr>

				<div class="d-flex justify-content-between">
					<span class="text-muted">
						<i class="ph-percent me-1"></i>
						Eficiência de Vendas
					</span>
					<span class="fw-semibold">
						{{ $stats['expected_revenue'] > 0 ? number_format(($stats['revenue'] / $stats['expected_revenue']) * 100, 1) : 0 }}%
					</span>
				</div>
			</div>
		</div>

		<!-- Timeline -->
		<div class="card mt-3">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="ph-clock-counter-clockwise me-2"></i>
					Histórico
				</h6>
			</div>
			<div class="card-body">
				<div class="timeline timeline-sm">
					<div class="timeline-item">
						<div class="timeline-point bg-primary"></div>
						<div class="timeline-content">
							<div class="fw-semibold">Horário Criado</div>
							<small class="text-muted">
								{{ $schedule->created_at->format('d/m/Y H:i') }}
								<br>
								por {{ $schedule->createdBy->name ?? 'Sistema' }}
							</small>
						</div>
					</div>

					@if($schedule->updated_at != $schedule->created_at)
						<div class="timeline-item">
							<div class="timeline-point bg-info"></div>
							<div class="timeline-content">
								<div class="fw-semibold">Última Atualização</div>
								<small class="text-muted">
									{{ $schedule->updated_at->format('d/m/Y H:i') }}
								</small>
							</div>
						</div>
					@endif

					@if($schedule->tickets()->whereIn('status', ['paid', 'validated'])->exists())
						<div class="timeline-item">
							<div class="timeline-point bg-success"></div>
							<div class="timeline-content">
								<div class="fw-semibold">Primeiro Bilhete Vendido</div>
								<small class="text-muted">
									{{ $schedule->tickets()->oldest()->first()->created_at->format('d/m/Y H:i') }}
								</small>
							</div>
						</div>
					@endif

					@if($schedule->status === 'full')
						<div class="timeline-item">
							<div class="timeline-point bg-warning"></div>
							<div class="timeline-content">
								<div class="fw-semibold">Lotação Completa</div>
								<small class="text-muted">Todos os lugares vendidos</small>
							</div>
						</div>
					@endif

					@if($schedule->status === 'cancelled')
						<div class="timeline-item">
							<div class="timeline-point bg-danger"></div>
							<div class="timeline-content">
								<div class="fw-semibold">Horário Cancelado</div>
								<small class="text-muted">
									{{ $schedule->updated_at->format('d/m/Y H:i') }}
								</small>
							</div>
						</div>
					@endif

					@if($schedule->status === 'departed')
						<div class="timeline-item">
							<div class="timeline-point bg-secondary"></div>
							<div class="timeline-content">
								<div class="fw-semibold">Viagem Concluída</div>
								<small class="text-muted">Autocarro partiu</small>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push('styles')
<style>
.timeline {
	position: relative;
	padding-left: 30px;
}

.timeline::before {
	content: '';
	position: absolute;
	left: 8px;
	top: 0;
	bottom: 0;
	width: 2px;
	background: #e5e7eb;
}

.timeline-item {
	position: relative;
	padding-bottom: 20px;
}

.timeline-item:last-child {
	padding-bottom: 0;
}

.timeline-point {
	position: absolute;
	left: -26px;
	top: 2px;
	width: 18px;
	height: 18px;
	border-radius: 50%;
	border: 3px solid #fff;
	box-shadow: 0 0 0 2px #e5e7eb;
}

.timeline-content {
	padding-top: 2px;
}
</style>
@endpush
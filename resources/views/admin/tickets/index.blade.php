@extends('layouts.app')

@section('content')
<div class="content">
	<!-- Page Header -->
	<div class="page-header page-header-light shadow mb-4">
		<div class="page-header-content d-flex">
			<div class="page-title">
				<h4>
					<i class="ph-ticket me-2"></i>
					<span class="fw-semibold">Bilhetes</span>
				</h4>
			</div>
			<div class="my-auto ms-auto">
				<a href="{{ route('tickets.create') }}" class="btn btn-primary">
					<i class="ph-plus me-2"></i>
					Emitir Bilhete
				</a>
			</div>
		</div>
	</div>
	<!-- /page header -->

	<!-- Statistics Cards -->
	<div class="row g-3 mb-3">
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body">
				<div class="d-flex align-items-center">
					<div class="flex-fill">
						<h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
						<span class="text-muted">Total de Bilhetes</span>
					</div>
					<i class="ph-ticket ph-2x text-primary opacity-75"></i>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body">
				<div class="d-flex align-items-center">
					<div class="flex-fill">
						<h4 class="mb-0">{{ number_format($stats['today']) }}</h4>
						<span class="text-muted">Bilhetes Hoje</span>
					</div>
					<i class="ph-calendar-check ph-2x text-success opacity-75"></i>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body">
				<div class="d-flex align-items-center">
					<div class="flex-fill">
						<h4 class="mb-0">{{ number_format($stats['revenue_today'], 2) }} MT</h4>
						<span class="text-muted">Receita Hoje</span>
					</div>
					<i class="ph-currency-circle-dollar ph-2x text-info opacity-75"></i>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body">
				<div class="d-flex align-items-center">
					<div class="flex-fill">
						<h4 class="mb-0">{{ number_format($stats['revenue_month'], 2) }} MT</h4>
						<span class="text-muted">Receita Este Mês</span>
					</div>
					<i class="ph-trend-up ph-2x text-warning opacity-75"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Status Statistics -->
	<div class="row g-3 mb-3">
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-warning bg-opacity-10 border-warning">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<h4 class="mb-0 text-warning">{{ number_format($stats['reserved']) }}</h4>
						<span class="text-warning">Reservados</span>
					</div>
					<i class="ph-clock ph-2x text-warning"></i>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-success bg-opacity-10 border-success">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<h4 class="mb-0 text-success">{{ number_format($stats['paid']) }}</h4>
						<span class="text-success">Pagos</span>
					</div>
					<i class="ph-check-circle ph-2x text-success"></i>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-info bg-opacity-10 border-info">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<h4 class="mb-0 text-info">{{ number_format($stats['validated']) }}</h4>
						<span class="text-info">Validados</span>
					</div>
					<i class="ph-seal-check ph-2x text-info"></i>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-danger bg-opacity-10 border-danger">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<h4 class="mb-0 text-danger">{{ number_format($stats['cancelled']) }}</h4>
						<span class="text-danger">Cancelados</span>
					</div>
					<i class="ph-x-circle ph-2x text-danger"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Filters Card -->
	<div class="card mb-3">
		<div class="card-header">
			<h6 class="mb-0">
				<i class="ph-funnel me-2"></i>
				Filtros
			</h6>
		</div>
		<div class="card-body">
			<form method="GET" action="{{ route('tickets.index') }}" class="row g-3">
				<!-- Número do Bilhete -->
				<div class="col-md-3">
					<label class="form-label">Número do Bilhete</label>
					<input type="text" name="ticket_number" class="form-control" 
						   placeholder="TKT-20250127-..." 
						   value="{{ request('ticket_number') }}">
				</div>

				<!-- Status -->
				<div class="col-md-2">
					<label class="form-label">Status</label>
					<select name="status" class="form-select">
						<option value="">Todos</option>
						<option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reservado</option>
						<option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Pago</option>
						<option value="validated" {{ request('status') === 'validated' ? 'selected' : '' }}>Validado</option>
						<option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
					</select>
				</div>

				<!-- Data -->
				<div class="col-md-2">
					<label class="form-label">Data da Viagem</label>
					<input type="date" name="date" class="form-control" 
						   value="{{ request('date') }}">
				</div>

				<!-- Rota -->
				<div class="col-md-3">
					<label class="form-label">Rota</label>
					<select name="route_id" class="form-select">
						<option value="">Todas</option>
						@foreach($routes as $route)
							<option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
								{{ $route->originCity->name }} → {{ $route->destinationCity->name }}
							</option>
						@endforeach
					</select>
				</div>

				<!-- Passageiro -->
				<div class="col-md-2">
					<label class="form-label">Passageiro</label>
					<input type="text" name="passenger_search" class="form-control" 
						   placeholder="Nome, email..." 
						   value="{{ request('passenger_search') }}">
				</div>

				<!-- Botões -->
				<div class="col-12 d-flex gap-2">
					<button type="submit" class="btn btn-primary">
						<i class="ph-funnel me-1"></i>
						Filtrar
					</button>
					<a href="{{ route('tickets.index') }}" class="btn btn-light">
						<i class="ph-x me-1"></i>
						Limpar
					</a>
					<a href="{{ route('tickets.validate') }}" class="btn btn-success ms-auto">
						<i class="ph-qr-code me-1"></i>
						Validar Bilhete
					</a>
				</div>
			</form>
		</div>
	</div>

	<!-- Tickets Table -->
	<div class="card">
		<div class="card-header d-flex align-items-center">
			<h5 class="mb-0">Lista de Bilhetes</h5>
			<div class="ms-auto">
				<span class="badge bg-primary">{{ $tickets->total() }} bilhetes</span>
			</div>
		</div>

		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
				<i class="ph-check-circle me-2"></i>
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		@if(session('error'))
			<div class="alert alert-danger alert-dismissible fade show m-3 mb-0" role="alert">
				<i class="ph-warning me-2"></i>
				{{ session('error') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 60px;">ID</th>
						<th>Nº Bilhete</th>
						<th>Passageiro</th>
						<th>Rota</th>
						<th>Data Viagem</th>
						<th style="width: 80px;" class="text-center">Assento</th>
						<th style="width: 100px;" class="text-end">Preço</th>
						<th style="width: 120px;" class="text-center">Status</th>
						<th style="width: 150px;" class="text-center">Ações</th>
					</tr>
				</thead>
				<tbody>
					@forelse($tickets as $ticket)
						<tr>
							<td>{{ $ticket->id }}</td>
							<td>
								<a href="{{ route('tickets.show', $ticket) }}" class="fw-semibold text-primary">
									{{ $ticket->ticket_number }}
								</a>
								<br>
								<small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
							</td>
							<td>
								<div>
									<div class="fw-semibold">{{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}</div>
									<small class="text-muted">{{ $ticket->passenger->email }}</small>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center gap-2">
									<span>{{ $ticket->schedule->route->originCity->name }}</span>
									<i class="ph-arrow-right text-muted"></i>
									<span>{{ $ticket->schedule->route->destinationCity->name }}</span>
								</div>
							</td>
							<td>
								<div>
									<div>{{ $ticket->schedule->departure_date->format('d/m/Y') }}</div>
									<small class="text-muted">{{ $ticket->schedule->departure_time }}</small>
								</div>
							</td>
							<td class="text-center">
								<span class="badge bg-secondary bg-opacity-25 text-secondary">
									<i class="ph-armchair me-1"></i>
									{{ $ticket->seat_number }}
								</span>
							</td>
							<td class="text-end">
								<span class="fw-semibold">{{ number_format($ticket->price, 2) }} MT</span>
							</td>
							<td class="text-center">
								@php
									$statusConfig = [
										'reserved' => ['class' => 'warning', 'icon' => 'clock', 'label' => 'Reservado'],
										'paid' => ['class' => 'success', 'icon' => 'check-circle', 'label' => 'Pago'],
										'validated' => ['class' => 'info', 'icon' => 'seal-check', 'label' => 'Validado'],
										'cancelled' => ['class' => 'danger', 'icon' => 'x-circle', 'label' => 'Cancelado'],
									];
									$status = $statusConfig[$ticket->status] ?? $statusConfig['reserved'];
								@endphp
								<span class="badge bg-{{ $status['class'] }} bg-opacity-10 text-{{ $status['class'] }}">
									<i class="ph-{{ $status['icon'] }} me-1"></i>
									{{ $status['label'] }}
								</span>
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
										<i class="ph-dots-three-vertical"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li>
											<a class="dropdown-item" href="{{ route('tickets.show', $ticket) }}">
												<i class="ph-eye me-2"></i>
												Ver Detalhes
											</a>
										</li>
										@if($ticket->status === 'paid')
											<li>
												<a class="dropdown-item text-success" href="#" onclick="validateTicket({{ $ticket->id }})">
													<i class="ph-check-circle me-2"></i>
													Validar
												</a>
											</li>
										@endif
										@if($ticket->isCancellable())
											<li>
												<hr class="dropdown-divider">
											</li>
											<li>
												<form action="{{ route('tickets.cancel', $ticket) }}" method="POST" 
													  onsubmit="return confirm('Tem certeza que deseja cancelar este bilhete?')">
													@csrf
													<button type="submit" class="dropdown-item text-danger">
														<i class="ph-x-circle me-2"></i>
														Cancelar
													</button>
												</form>
											</li>
										@endif
										<li>
											<hr class="dropdown-divider">
										</li>
										<li>
											<form action="{{ route('tickets.resend-email', $ticket) }}" method="POST">
												@csrf
												<button type="submit" class="dropdown-item">
													<i class="ph-paper-plane-tilt me-2"></i>
													Reenviar Email
												</button>
											</form>
										</li>
										<li>
											<a class="dropdown-item" href="{{ route('tickets.pdf.download', $ticket) }}" target="_blank">
												<i class="ph-file-pdf me-2"></i>
												Download PDF
											</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="9" class="text-center py-5">
								<i class="ph-ticket ph-3x text-muted mb-3 d-block"></i>
								<p class="text-muted mb-0">Nenhum bilhete encontrado</p>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		@if($tickets->hasPages())
			<div class="card-footer">
				{{ $tickets->links() }}
			</div>
		@endif
	</div>
</div>
@endsection

@push('scripts')
<script>
function validateTicket(ticketId) {
	if (!confirm('Confirma a validação deste bilhete? Esta ação não pode ser desfeita.')) {
		return;
	}

	fetch(`/tickets/${ticketId}/validate`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			alert('✅ Bilhete validado com sucesso!\n\nPassageiro: ' + data.ticket.passenger_name);
			location.reload();
		} else {
			alert('❌ ' + data.message);
		}
	})
	.catch(error => {
		console.error('Erro:', error);
		alert('Erro ao validar bilhete. Tente novamente.');
	});
}

// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
	setTimeout(function() {
		const alerts = document.querySelectorAll('.alert-dismissible');
		alerts.forEach(function(alert) {
			const bsAlert = new bootstrap.Alert(alert);
			bsAlert.close();
		});
	}, 5000);
});
</script>
@endpush
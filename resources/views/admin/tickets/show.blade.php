@extends('layouts.app')

@section('content')
<div class="content">
	<!-- Page Header -->
	<div class="page-header page-header-light shadow mb-4">
		<div class="page-header-content d-flex">
			<div class="page-title">
				<h4>
					<a href="{{ route('tickets.index') }}"><i class="ph-arrow-left me-2"></i></a>
					<span class="fw-semibold">Detalhes do Bilhete</span>
					<span class="text-muted ms-2">{{ $ticket->ticket_number }}</span>
				</h4>
			</div>
			<div class="my-auto ms-auto">
				<div class="btn-group">
					<form action="{{ route('tickets.resend-email', $ticket) }}" method="POST" class="d-inline">
						@csrf
						<button type="submit" class="btn btn-light">
							<i class="ph-paper-plane-tilt me-2"></i>
							Reenviar Email
						</button>
					</form>
					<a href="{{ route('tickets.pdf.download', $ticket) }}" class="btn btn-light" target="_blank">
						<i class="ph-file-pdf me-2"></i>
						Download PDF
					</a>
				</div>
			</div>
		</div>
	</div>
	<!-- /page header -->

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<i class="ph-check-circle me-2"></i>
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	@endif

	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<i class="ph-warning me-2"></i>
			{{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	@endif

	<div class="row">
		<!-- Coluna Principal -->
		<div class="col-lg-8">
			<!-- Informações do Bilhete -->
			<div class="card">
				<div class="card-header bg-light">
					<h5 class="mb-0">
						<i class="ph-ticket me-2"></i>
						Informações do Bilhete
					</h5>
				</div>
				<div class="card-body">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="text-muted small">Número do Bilhete</label>
							<div class="fw-bold fs-5">{{ $ticket->ticket_number }}</div>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Status</label>
							<div>
								@php
									$statusConfig = [
										'reserved' => ['class' => 'warning', 'icon' => 'clock', 'label' => 'Reservado'],
										'paid' => ['class' => 'success', 'icon' => 'check-circle', 'label' => 'Pago'],
										'validated' => ['class' => 'info', 'icon' => 'seal-check', 'label' => 'Validado'],
										'cancelled' => ['class' => 'danger', 'icon' => 'x-circle', 'label' => 'Cancelado'],
									];
									$status = $statusConfig[$ticket->status] ?? $statusConfig['reserved'];
								@endphp
								<span class="badge bg-{{ $status['class'] }} fs-6 px-3 py-2">
									<i class="ph-{{ $status['icon'] }} me-1"></i>
									{{ $status['label'] }}
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Preço</label>
							<div class="fw-bold text-success fs-5">{{ number_format($ticket->price, 2) }} MT</div>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Assento</label>
							<div>
								<span class="badge bg-secondary bg-opacity-25 text-secondary fs-6 px-3 py-2">
									<i class="ph-armchair me-1"></i>
									{{ $ticket->seat_number }}
								</span>
							</div>
						</div>
						<div class="col-12">
							<hr>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Data de Emissão</label>
							<div>{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
						</div>
						@if($ticket->validated_at)
							<div class="col-md-6">
								<label class="text-muted small">Data de Validação</label>
								<div>{{ $ticket->validated_at->format('d/m/Y H:i') }}</div>
							</div>
						@endif
					</div>
				</div>
			</div>

			<!-- Dados da Viagem -->
			<div class="card mt-3">
				<div class="card-header bg-light">
					<h5 class="mb-0">
						<i class="ph-map-trifold me-2"></i>
						Dados da Viagem
					</h5>
				</div>
				<div class="card-body">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="text-muted small">Origem</label>
							<div class="fw-semibold fs-6">
								<i class="ph-map-pin me-1 text-primary"></i>
								{{ $ticket->schedule->route->originCity->name }}
							</div>
							<small class="text-muted">{{ $ticket->schedule->route->originCity->province->name }}</small>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Destino</label>
							<div class="fw-semibold fs-6">
								<i class="ph-map-pin me-1 text-danger"></i>
								{{ $ticket->schedule->route->destinationCity->name }}
							</div>
							<small class="text-muted">{{ $ticket->schedule->route->destinationCity->province->name }}</small>
						</div>
						<div class="col-12">
							<hr>
						</div>
						<div class="col-md-4">
							<label class="text-muted small">Data de Partida</label>
							<div class="fw-semibold">{{ $ticket->schedule->departure_date->format('d/m/Y') }}</div>
						</div>
						<div class="col-md-4">
							<label class="text-muted small">Hora de Partida</label>
							<div class="fw-semibold">{{ $ticket->schedule->departure_time }}</div>
						</div>
						<div class="col-md-4">
							<label class="text-muted small">Distância</label>
							<div>{{ number_format($ticket->schedule->route->distance_km, 0) }} km</div>
						</div>
						<div class="col-12">
							<hr>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Autocarro</label>
							<div class="fw-semibold">{{ $ticket->schedule->bus->model }}</div>
							<small class="text-muted">{{ $ticket->schedule->bus->registration_number }}</small>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Capacidade</label>
							<div>{{ $ticket->schedule->bus->total_seats }} lugares</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Dados do Passageiro -->
			<div class="card mt-3">
				<div class="card-header bg-light">
					<h5 class="mb-0">
						<i class="ph-user me-2"></i>
						Dados do Passageiro
					</h5>
				</div>
				<div class="card-body">
					<div class="row g-3">
						<div class="col-md-12">
							<label class="text-muted small">Nome Completo</label>
							<div class="fw-semibold fs-6">{{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}</div>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Email</label>
							<div>
								<i class="ph-envelope me-1"></i>
								<a href="mailto:{{ $ticket->passenger->email }}">{{ $ticket->passenger->email }}</a>
							</div>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Telefone</label>
							<div>
								<i class="ph-phone me-1"></i>
								<a href="tel:{{ $ticket->passenger->phone }}">{{ $ticket->passenger->phone }}</a>
							</div>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Tipo de Documento</label>
							<div class="text-uppercase">{{ $ticket->passenger->document_type }}</div>
						</div>
						<div class="col-md-6">
							<label class="text-muted small">Número do Documento</label>
							<div>{{ $ticket->passenger->document_number }}</div>
						</div>
					</div>
				</div>
			</div>

			@if($ticket->validatedBy)
				<!-- Informações de Validação -->
				<div class="card mt-3">
					<div class="card-header bg-info bg-opacity-10">
						<h5 class="mb-0 text-info">
							<i class="ph-seal-check me-2"></i>
							Validação
						</h5>
					</div>
					<div class="card-body">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="text-muted small">Validado por</label>
								<div class="fw-semibold">{{ $ticket->validatedBy->name }}</div>
							</div>
							<div class="col-md-6">
								<label class="text-muted small">Data/Hora</label>
								<div>{{ $ticket->validated_at->format('d/m/Y H:i:s') }}</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>

		<!-- Coluna Lateral -->
		<div class="col-lg-4">
			<!-- QR Code -->
			@if($ticket->qr_code)
				<div class="card">
					<div class="card-header bg-light text-center">
						<h5 class="mb-0">
							<i class="ph-qr-code me-2"></i>
							QR Code
						</h5>
					</div>
					<div class="card-body text-center">
						<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($ticket->qr_code) }}" 
							 alt="QR Code"
							 class="img-fluid mb-3"
							 style="max-width: 250px;">
						<p class="text-muted small mb-0">
							<i class="ph-info-circle me-1"></i>
							Apresente este código no embarque
						</p>
					</div>
				</div>
			@endif

			<!-- Ações Disponíveis -->
			<div class="card mt-3">
				<div class="card-header bg-light">
					<h5 class="mb-0">
						<i class="ph-lightning me-2"></i>
						Ações Disponíveis
					</h5>
				</div>
				<div class="card-body">
					<div class="d-grid gap-2">
						@if($ticket->status === 'paid')
							<button type="button" class="btn btn-success" onclick="validateTicket()">
								<i class="ph-check-circle me-2"></i>
								Validar Bilhete
							</button>
						@endif

						@if($ticket->isCancellable())
							<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
								<i class="ph-x-circle me-2"></i>
								Cancelar Bilhete
							</button>
						@endif

						<form action="{{ route('tickets.resend-email', $ticket) }}" method="POST">
							@csrf
							<button type="submit" class="btn btn-light w-100">
								<i class="ph-paper-plane-tilt me-2"></i>
								Reenviar Email
							</button>
						</form>

						<a href="{{ route('tickets.pdf.download', $ticket) }}" class="btn btn-light" target="_blank">
							<i class="ph-file-pdf me-2"></i>
							Download PDF
						</a>

						<a href="{{ route('schedules.show', $ticket->schedule) }}" class="btn btn-light">
							<i class="ph-calendar me-2"></i>
							Ver Horário
						</a>

						<a href="{{ route('passengers.show', $ticket->passenger) }}" class="btn btn-light">
							<i class="ph-user me-2"></i>
							Ver Passageiro
						</a>
					</div>
				</div>
			</div>

			<!-- Informações Adicionais -->
			<div class="card mt-3">
				<div class="card-header bg-light">
					<h5 class="mb-0">
						<i class="ph-info me-2"></i>
						Informações
					</h5>
				</div>
				<div class="card-body">
					<ul class="list-unstyled mb-0">
						<li class="mb-2">
							<i class="ph-clock text-muted me-2"></i>
							<small class="text-muted">Criado há {{ $ticket->created_at->diffForHumans() }}</small>
						</li>
						@if($ticket->updated_at != $ticket->created_at)
							<li class="mb-2">
								<i class="ph-pencil text-muted me-2"></i>
								<small class="text-muted">Atualizado há {{ $ticket->updated_at->diffForHumans() }}</small>
							</li>
						@endif
						@if($ticket->validated_at)
							<li class="mb-2">
								<i class="ph-seal-check text-success me-2"></i>
								<small class="text-muted">Validado há {{ $ticket->validated_at->diffForHumans() }}</small>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('tickets.cancel', $ticket) }}" method="POST">
				@csrf
				<div class="modal-header bg-danger text-white">
					<h5 class="modal-title">
						<i class="ph-warning me-2"></i>
						Cancelar Bilhete
					</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						<i class="ph-warning me-2"></i>
						<strong>Atenção!</strong> Esta ação não pode ser desfeita.
					</div>
					<p>Tem certeza que deseja cancelar este bilhete?</p>
					<div class="mb-3">
						<label class="form-label">Motivo do Cancelamento (opcional)</label>
						<textarea name="reason" class="form-control" rows="3" placeholder="Digite o motivo..."></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-danger">
						<i class="ph-x-circle me-2"></i>
						Confirmar Cancelamento
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
function validateTicket() {
	if (!confirm('Confirma a validação deste bilhete?\n\nBilhete: {{ $ticket->ticket_number }}\nPassageiro: {{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}\n\nEsta ação não pode ser desfeita.')) {
		return;
	}

	fetch("{{ route('tickets.validate', $ticket) }}", {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			alert('✅ Bilhete validado com sucesso!');
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
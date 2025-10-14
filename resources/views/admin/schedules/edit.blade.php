@extends('layouts.app')

@section('title', 'Editar Horário')

@section('page-title')
	Horários - <span class="fw-normal">Editar</span>
@endsection

@section('breadcrumbs')
	<a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
	<a href="{{ route('schedules.index') }}" class="breadcrumb-item">Horários</a>
	<a href="{{ route('schedules.show', $schedule) }}" class="breadcrumb-item">Detalhes</a>
	<span class="breadcrumb-item active">Editar</span>
@endsection

@section('content')

<div class="row">
	<div class="col-lg-8 offset-lg-2">
		@if($hasTickets)
			<div class="alert alert-warning">
				<div class="d-flex align-items-center">
					<i class="ph-warning ph-2x me-3"></i>
					<div>
						<h6 class="mb-1">Atenção!</h6>
						<p class="mb-0">
							Este horário já possui bilhetes vendidos. 
							Alterações podem afetar os passageiros. 
							Proceda com cuidado.
						</p>
					</div>
				</div>
			</div>
		@endif

		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="ph-pencil me-2"></i>
					Editar Horário de Viagem
				</h5>
			</div>

			<form action="{{ route('schedules.update', $schedule) }}" method="POST">
				@csrf
				@method('PUT')

				<div class="card-body">
					@if($errors->any())
						<div class="alert alert-danger alert-dismissible fade show">
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
							<h6 class="mb-2">
								<i class="ph-warning-circle me-2"></i>
								Erros encontrados:
							</h6>
							<ul class="mb-0">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<!-- Informação Atual -->
					<div class="alert alert-info mb-4">
						<h6 class="mb-2">
							<i class="ph-info me-2"></i>
							Informação Actual
						</h6>
						<div class="row small">
							<div class="col-md-6">
								<strong>Rota:</strong> 
								{{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}
							</div>
							<div class="col-md-6">
								<strong>Autocarro:</strong> 
								{{ $schedule->bus->registration_number }} ({{ $schedule->bus->model }})
							</div>
							<div class="col-md-6 mt-2">
								<strong>Data/Hora:</strong> 
								{{ $schedule->departure_date->format('d/m/Y') }} às {{ $schedule->departure_time }}
							</div>
							<div class="col-md-6 mt-2">
								<strong>Preço:</strong> 
								{{ number_format($schedule->price, 2) }} MT
							</div>
						</div>
					</div>

					<!-- Rota -->
					<div class="mb-4">
						<label class="form-label fw-semibold">
							Rota <span class="text-danger">*</span>
						</label>
						<select name="route_id" id="route_id" 
								class="form-select @error('route_id') is-invalid @enderror" 
								required
								{{ $hasTickets ? 'disabled' : '' }}>
							<option value="">Selecione a rota...</option>
							@foreach($routes as $route)
								<option value="{{ $route->id }}" 
										data-distance="{{ $route->distance }}"
										data-duration="{{ $route->estimated_duration }}"
										{{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}>
									{{ $route->originCity->name }} → {{ $route->destinationCity->name }}
									({{ number_format($route->distance, 0) }} km)
								</option>
							@endforeach
						</select>
						@error('route_id')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						@if($hasTickets)
							<input type="hidden" name="route_id" value="{{ $schedule->route_id }}">
							<div class="form-text text-warning">
								<i class="ph-lock me-1"></i>
								Não é possível alterar a rota com bilhetes vendidos
							</div>
						@else
							<div class="form-text">Selecione a rota da viagem</div>
						@endif
					</div>

					<!-- Autocarro -->
					<div class="mb-4">
						<label class="form-label fw-semibold">
							Autocarro <span class="text-danger">*</span>
						</label>
						<select name="bus_id" id="bus_id" 
								class="form-select @error('bus_id') is-invalid @enderror" 
								required>
							<option value="">Selecione o autocarro...</option>
							@foreach($buses as $bus)
								<option value="{{ $bus->id }}" 
										data-seats="{{ $bus->total_seats }}"
										data-model="{{ $bus->model }}"
										{{ old('bus_id', $schedule->bus_id) == $bus->id ? 'selected' : '' }}>
									{{ $bus->registration_number }} - {{ $bus->model }}
									({{ $bus->total_seats }} lugares)
								</option>
							@endforeach
						</select>
						@error('bus_id')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						@if($hasTickets)
							<div class="form-text text-warning">
								<i class="ph-warning me-1"></i>
								Certifique-se que o novo autocarro tem capacidade suficiente para os bilhetes já vendidos
							</div>
						@else
							<div class="form-text">Selecione o autocarro para esta viagem</div>
						@endif
					</div>

					<!-- Data e Hora -->
					<div class="row mb-4">
						<div class="col-md-6">
							<label class="form-label fw-semibold">
								Data de Partida <span class="text-danger">*</span>
							</label>
							<input type="date" 
								   name="departure_date" 
								   class="form-control @error('departure_date') is-invalid @enderror" 
								   value="{{ old('departure_date', $schedule->departure_date->format('Y-m-d')) }}"
								   min="{{ date('Y-m-d') }}"
								   required>
							@error('departure_date')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
							@if($hasTickets)
								<div class="form-text text-warning">
									<i class="ph-warning me-1"></i>
									Alterações de data devem ser comunicadas aos passageiros
								</div>
							@endif
						</div>

						<div class="col-md-6">
							<label class="form-label fw-semibold">
								Hora de Partida <span class="text-danger">*</span>
							</label>
							<input type="time" 
								   name="departure_time" 
								   class="form-control @error('departure_time') is-invalid @enderror" 
								   value="{{ old('departure_time', $schedule->departure_time) }}"
								   required>
							@error('departure_time')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>

					<!-- Preço -->
					<div class="mb-4">
						<label class="form-label fw-semibold">
							Preço do Bilhete <span class="text-danger">*</span>
						</label>
						<div class="input-group">
							<input type="number" 
								   name="price" 
								   class="form-control @error('price') is-invalid @enderror" 
								   value="{{ old('price', $schedule->price) }}"
								   min="0"
								   step="0.01"
								   required
								   {{ $hasTickets ? 'readonly' : '' }}>
							<span class="input-group-text">MT</span>
							@error('price')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						@if($hasTickets)
							<div class="form-text text-warning">
								<i class="ph-lock me-1"></i>
								Não é possível alterar o preço com bilhetes vendidos
							</div>
						@else
							<div class="form-text">Preço por passageiro em Meticais (MT)</div>
						@endif
					</div>

					<!-- Status -->
					<div class="mb-4">
						<label class="form-label fw-semibold">
							Status <span class="text-danger">*</span>
						</label>
						<select name="status" class="form-select @error('status') is-invalid @enderror" required>
							<option value="active" {{ old('status', $schedule->status) == 'active' ? 'selected' : '' }}>
								Activo
							</option>
							<option value="full" {{ old('status', $schedule->status) == 'full' ? 'selected' : '' }}>
								Lotado
							</option>
							<option value="departed" {{ old('status', $schedule->status) == 'departed' ? 'selected' : '' }}>
								Partiu
							</option>
							<option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>
								Cancelado
							</option>
						</select>
						@error('status')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						<div class="form-text">
							<ul class="mb-0 mt-1 small">
								<li><strong>Activo:</strong> Disponível para venda de bilhetes</li>
								<li><strong>Lotado:</strong> Todos os lugares vendidos</li>
								<li><strong>Partiu:</strong> Viagem já realizada</li>
								<li><strong>Cancelado:</strong> Viagem cancelada</li>
							</ul>
						</div>
					</div>

					<!-- Aviso sobre alterações -->
					@if($hasTickets)
						<div class="alert alert-warning">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="confirm-changes" required>
								<label class="form-check-label" for="confirm-changes">
									<strong>Confirmo que compreendo que:</strong>
									<ul class="mb-0 mt-2">
										<li>Este horário possui bilhetes vendidos</li>
										<li>Algumas alterações podem afetar os passageiros</li>
										<li>Os passageiros devem ser notificados sobre mudanças significativas</li>
									</ul>
								</label>
							</div>
						</div>
					@endif
				</div>

				<div class="card-footer d-flex justify-content-between">
					<a href="{{ route('schedules.show', $schedule) }}" class="btn btn-light">
						<i class="ph-arrow-left me-2"></i>
						Cancelar
					</a>
					<button type="submit" class="btn btn-primary">
						<i class="ph-check-circle me-2"></i>
						Salvar Alterações
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
	const busSelect = document.getElementById('bus_id');
	const hasTickets = {{ $hasTickets ? 'true' : 'false' }};
	const bookedSeats = {{ $schedule->tickets()->whereIn('status', ['reserved', 'paid', 'validated'])->count() }};

	// Validar capacidade do autocarro ao mudar
	if (hasTickets) {
		busSelect.addEventListener('change', function() {
			const option = this.options[this.selectedIndex];
			const totalSeats = parseInt(option.dataset.seats) || 0;

			if (totalSeats < bookedSeats) {
				alert(`ATENÇÃO: Este autocarro tem apenas ${totalSeats} lugares, mas já existem ${bookedSeats} bilhetes vendidos!\n\nPor favor, selecione um autocarro com capacidade suficiente.`);
				this.value = '{{ $schedule->bus_id }}';
			}
		});
	}
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Novo Horário')

@section('page-title')
	Horários - <span class="fw-normal">Novo Horário</span>
@endsection

@section('breadcrumbs')
	<a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
	<a href="{{ route('schedules.index') }}" class="breadcrumb-item">Horários</a>
	<span class="breadcrumb-item active">Novo Horário</span>
@endsection

@section('content')

<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="ph-calendar-plus me-2"></i>
					Criar Novo Horário de Viagem
				</h5>
			</div>

			<form action="{{ route('schedules.store') }}" method="POST">
				@csrf

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

					<!-- Rota -->
					<div class="mb-4">
						<label class="form-label fw-semibold">
							Rota <span class="text-danger">*</span>
						</label>
						<select name="route_id" id="route_id" 
								class="form-select @error('route_id') is-invalid @enderror" 
								required>
							<option value="">Selecione a rota...</option>
							@foreach($routes as $route)
								<option value="{{ $route->id }}" 
										data-distance="{{ $route->distance }}"
										data-duration="{{ $route->estimated_duration }}"
										{{ old('route_id') == $route->id ? 'selected' : '' }}>
									{{ $route->originCity->name }} → {{ $route->destinationCity->name }}
									({{ number_format($route->distance, 0) }} km)
								</option>
							@endforeach
						</select>
						@error('route_id')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						<div class="form-text">Selecione a rota da viagem</div>
					</div>

					<!-- Informações da Rota Selecionada -->
					<div id="route-info" class="alert alert-info d-none mb-4">
						<div class="d-flex align-items-center">
							<i class="ph-info ph-2x me-3"></i>
							<div class="flex-fill">
								<div class="fw-semibold mb-1">Informações da Rota</div>
								<div class="row text-sm">
									<div class="col-md-6">
										<i class="ph-road-horizon me-1"></i>
										Distância: <span id="route-distance" class="fw-semibold">-</span> km
									</div>
									<div class="col-md-6">
										<i class="ph-clock me-1"></i>
										Duração estimada: <span id="route-duration" class="fw-semibold">-</span>
									</div>
								</div>
							</div>
						</div>
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
										{{ old('bus_id') == $bus->id ? 'selected' : '' }}>
									{{ $bus->registration_number }} - {{ $bus->model }}
									({{ $bus->total_seats }} lugares)
								</option>
							@endforeach
						</select>
						@error('bus_id')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						<div class="form-text">Selecione o autocarro para esta viagem</div>
					</div>

					<!-- Informações do Autocarro -->
					<div id="bus-info" class="alert alert-secondary d-none mb-4">
						<div class="d-flex align-items-center">
							<i class="ph-bus ph-2x me-3"></i>
							<div class="flex-fill">
								<div class="fw-semibold mb-1">Informações do Autocarro</div>
								<div class="row text-sm">
									<div class="col-md-6">
										<i class="ph-armchair me-1"></i>
										Total de lugares: <span id="bus-seats" class="fw-semibold">-</span>
									</div>
									<div class="col-md-6">
										<i class="ph-bus me-1"></i>
										Modelo: <span id="bus-model" class="fw-semibold">-</span>
									</div>
								</div>
							</div>
						</div>
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
								   value="{{ old('departure_date', date('Y-m-d')) }}"
								   min="{{ date('Y-m-d') }}"
								   required>
							@error('departure_date')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
							<div class="form-text">Data da partida</div>
						</div>

						<div class="col-md-6">
							<label class="form-label fw-semibold">
								Hora de Partida <span class="text-danger">*</span>
							</label>
							<input type="time" 
								   name="departure_time" 
								   class="form-control @error('departure_time') is-invalid @enderror" 
								   value="{{ old('departure_time', '08:00') }}"
								   required>
							@error('departure_time')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
							<div class="form-text">Hora da partida (formato 24h)</div>
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
								   value="{{ old('price') }}"
								   min="0"
								   step="0.01"
								   placeholder="0.00"
								   required>
							<span class="input-group-text">MT</span>
							@error('price')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="form-text">Preço por passageiro em Meticais (MT)</div>
					</div>

					<!-- Sugestão de Preço -->
					<div id="price-suggestion" class="alert alert-warning d-none mb-4">
						<div class="d-flex align-items-center">
							<i class="ph-lightbulb ph-2x me-3"></i>
							<div class="flex-fill">
								<div class="fw-semibold mb-1">Sugestão de Preço</div>
								<p class="mb-2">
									Com base na distância da rota (<span id="suggestion-distance">-</span> km), 
									sugerimos um preço de aproximadamente 
									<span id="suggested-price" class="fw-semibold">-</span> MT por passageiro.
								</p>
								<button type="button" class="btn btn-sm btn-warning" onclick="applySuggestedPrice()">
									<i class="ph-check me-1"></i>
									Aplicar Preço Sugerido
								</button>
							</div>
						</div>
					</div>

					<!-- Status -->
					<div class="mb-4">
						<label class="form-label fw-semibold">
							Status Inicial
						</label>
						<select name="status" class="form-select">
							<option value="active" selected>Activo</option>
							<option value="full">Lotado</option>
						</select>
						<div class="form-text">Status inicial do horário (normalmente "Activo")</div>
					</div>

					<!-- Resumo -->
					<div id="schedule-summary" class="card bg-light d-none">
						<div class="card-body">
							<h6 class="mb-3">
								<i class="ph-list-checks me-2"></i>
								Resumo do Horário
							</h6>
							<div class="row">
								<div class="col-md-6 mb-2">
									<small class="text-muted d-block">Rota</small>
									<span id="summary-route" class="fw-semibold">-</span>
								</div>
								<div class="col-md-6 mb-2">
									<small class="text-muted d-block">Autocarro</small>
									<span id="summary-bus" class="fw-semibold">-</span>
								</div>
								<div class="col-md-6 mb-2">
									<small class="text-muted d-block">Data e Hora</small>
									<span id="summary-datetime" class="fw-semibold">-</span>
								</div>
								<div class="col-md-6 mb-2">
									<small class="text-muted d-block">Preço</small>
									<span id="summary-price" class="fw-semibold">-</span>
								</div>
								<div class="col-md-12 mt-2">
									<small class="text-muted d-block">Receita Potencial (todos lugares vendidos)</small>
									<span id="summary-revenue" class="fw-semibold text-success">-</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer d-flex justify-content-between">
					<a href="{{ route('schedules.index') }}" class="btn btn-light">
						<i class="ph-arrow-left me-2"></i>
						Voltar
					</a>
					<button type="submit" class="btn btn-primary">
						<i class="ph-check-circle me-2"></i>
						Criar Horário
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
	const routeSelect = document.getElementById('route_id');
	const busSelect = document.getElementById('bus_id');
	const priceInput = document.querySelector('input[name="price"]');
	const dateInput = document.querySelector('input[name="departure_date"]');
	const timeInput = document.querySelector('input[name="departure_time"]');

	let suggestedPrice = 0;

	// Quando selecionar rota
	routeSelect.addEventListener('change', function() {
		const option = this.options[this.selectedIndex];
		const distance = option.dataset.distance;
		const duration = option.dataset.duration;

		if (distance) {
			// Mostrar info da rota
			document.getElementById('route-distance').textContent = parseFloat(distance).toFixed(0);
			document.getElementById('route-duration').textContent = duration || '-';
			document.getElementById('route-info').classList.remove('d-none');

			// Calcular e mostrar sugestão de preço (aproximadamente 2 MT por km)
			suggestedPrice = Math.ceil(parseFloat(distance) * 2);
			document.getElementById('suggestion-distance').textContent = parseFloat(distance).toFixed(0);
			document.getElementById('suggested-price').textContent = suggestedPrice.toFixed(2);
			document.getElementById('price-suggestion').classList.remove('d-none');
		} else {
			document.getElementById('route-info').classList.add('d-none');
			document.getElementById('price-suggestion').classList.add('d-none');
		}

		updateSummary();
	});

	// Quando selecionar autocarro
	busSelect.addEventListener('change', function() {
		const option = this.options[this.selectedIndex];
		const seats = option.dataset.seats;
		const model = option.dataset.model;

		if (seats) {
			document.getElementById('bus-seats').textContent = seats;
			document.getElementById('bus-model').textContent = model;
			document.getElementById('bus-info').classList.remove('d-none');
		} else {
			document.getElementById('bus-info').classList.add('d-none');
		}

		updateSummary();
	});

	// Atualizar resumo quando mudar preço, data ou hora
	priceInput.addEventListener('input', updateSummary);
	dateInput.addEventListener('change', updateSummary);
	timeInput.addEventListener('change', updateSummary);

	// Função para aplicar preço sugerido
	window.applySuggestedPrice = function() {
		priceInput.value = suggestedPrice.toFixed(2);
		updateSummary();
	};

	// Função para atualizar resumo
	function updateSummary() {
		const routeOption = routeSelect.options[routeSelect.selectedIndex];
		const busOption = busSelect.options[busSelect.selectedIndex];
		const price = parseFloat(priceInput.value) || 0;
		const date = dateInput.value;
		const time = timeInput.value;

		// Verificar se tem dados suficientes
		if (routeSelect.value && busSelect.value && price > 0 && date && time) {
			// Atualizar resumo
			document.getElementById('summary-route').textContent = routeOption.text;
			document.getElementById('summary-bus').textContent = busOption.text;
			document.getElementById('summary-datetime').textContent = 
				formatDate(date) + ' às ' + time;
			document.getElementById('summary-price').textContent = price.toFixed(2) + ' MT';

			// Calcular receita potencial
			const totalSeats = parseInt(busOption.dataset.seats) || 0;
			const potentialRevenue = totalSeats * price;
			document.getElementById('summary-revenue').textContent = 
				potentialRevenue.toFixed(2) + ' MT (' + totalSeats + ' × ' + price.toFixed(2) + ' MT)';

			document.getElementById('schedule-summary').classList.remove('d-none');
		} else {
			document.getElementById('schedule-summary').classList.add('d-none');
		}
	}

	// Função auxiliar para formatar data
	function formatDate(dateString) {
		if (!dateString) return '-';
		const parts = dateString.split('-');
		return parts[2] + '/' + parts[1] + '/' + parts[0];
	}

	// Trigger inicial se tiver valores
	if (routeSelect.value) routeSelect.dispatchEvent(new Event('change'));
	if (busSelect.value) busSelect.dispatchEvent(new Event('change'));
});
</script>
@endpush
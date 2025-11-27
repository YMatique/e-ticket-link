@extends('layouts.app')

@section('content')
<div class="content">
	<!-- Page Header -->
	<div class="page-header page-header-light shadow mb-4">
		<div class="page-header-content d-flex">
			<div class="page-title">
				<h4>
					<a href="{{ route('tickets.index') }}"><i class="ph-arrow-left me-2"></i></a>
					<span class="fw-semibold">Emitir Bilhete</span>
				</h4>
			</div>
		</div>
	</div>
	<!-- /page header -->

	<form action="{{ route('tickets.store') }}" method="POST" id="ticketForm">
		@csrf

		<div class="row">
			<!-- Formulário Principal -->
			<div class="col-lg-8">
				<!-- Passo 1: Selecionar Horário -->
				<div class="card mb-3">
					<div class="card-header bg-primary text-white">
						<h5 class="mb-0">
							<i class="ph-calendar me-2"></i>
							Passo 1: Selecionar Horário
						</h5>
					</div>
					<div class="card-body">
						<!-- Filtros de Horário -->
						<div class="row g-3 mb-3">
							<div class="col-md-4">
								<label class="form-label">Data da Viagem <span class="text-danger">*</span></label>
								<input type="date" class="form-control" id="filter_date" 
									   min="{{ date('Y-m-d') }}" 
									   value="{{ date('Y-m-d') }}">
							</div>
							<div class="col-md-4">
								<label class="form-label">Rota</label>
								<select class="form-select" id="filter_route">
									<option value="">Todas as rotas</option>
									@foreach($routes as $route)
										<option value="{{ $route->id }}">
											{{ $route->originCity->name }} → {{ $route->destinationCity->name }}
										</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-4 d-flex align-items-end">
								<button type="button" class="btn btn-primary w-100" onclick="filterSchedules()">
									<i class="ph-funnel me-1"></i>
									Filtrar
								</button>
							</div>
						</div>

						<!-- Lista de Horários -->
						<div id="schedules-list">
							<div class="text-center py-4">
								<i class="ph-calendar-blank ph-3x text-muted mb-2 d-block"></i>
								<p class="text-muted">Selecione uma data e clique em Filtrar</p>
							</div>
						</div>

						<input type="hidden" name="schedule_id" id="schedule_id" value="{{ $preselectedScheduleId }}">
					</div>
				</div>

				<!-- Passo 2: Selecionar Assento -->
				<div class="card mb-3" id="step2-card" style="display: none;">
					<div class="card-header bg-success text-white">
						<h5 class="mb-0">
							<i class="ph-armchair me-2"></i>
							Passo 2: Selecionar Assento
						</h5>
					</div>
					<div class="card-body">
						<div id="seat-selection-area">
							<!-- Será carregado via AJAX -->
						</div>
						<input type="hidden" name="seat_number" id="seat_number" value="{{ $preselectedSeat }}">
					</div>
				</div>

				<!-- Passo 3: Dados do Passageiro -->
				<div class="card mb-3" id="step3-card" style="display: none;">
					<div class="card-header bg-info text-white">
						<h5 class="mb-0">
							<i class="ph-user me-2"></i>
							Passo 3: Dados do Passageiro
						</h5>
					</div>
					<div class="card-body">
						<!-- Buscar Passageiro Existente -->
						<div class="alert alert-info">
							<i class="ph-info me-2"></i>
							<strong>Dica:</strong> Busque o passageiro por email ou telefone antes de criar um novo.
						</div>

						<div class="row g-3 mb-3">
							<div class="col-md-8">
								<label class="form-label">Buscar Passageiro Existente</label>
								<input type="text" class="form-control" id="passenger_search" 
									   placeholder="Digite email ou telefone...">
							</div>
							<div class="col-md-4 d-flex align-items-end">
								<button type="button" class="btn btn-light w-100" onclick="searchPassenger()">
									<i class="ph-magnifying-glass me-1"></i>
									Buscar
								</button>
							</div>
						</div>

						<div id="passenger-search-results" class="mb-3"></div>

						<input type="hidden" name="passenger_id" id="passenger_id">

						<hr>

						<!-- Formulário de Novo Passageiro -->
						<h6 class="mb-3">
							<i class="ph-user-plus me-2"></i>
							Criar Novo Passageiro
						</h6>

						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">Primeiro Nome <span class="text-danger">*</span></label>
								<input type="text" class="form-control @error('first_name') is-invalid @enderror" 
									   name="first_name" id="first_name" value="{{ old('first_name') }}">
								@error('first_name')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="col-md-6">
								<label class="form-label">Último Nome <span class="text-danger">*</span></label>
								<input type="text" class="form-control @error('last_name') is-invalid @enderror" 
									   name="last_name" id="last_name" value="{{ old('last_name') }}">
								@error('last_name')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="col-md-6">
								<label class="form-label">Email <span class="text-danger">*</span></label>
								<input type="email" class="form-control @error('email') is-invalid @enderror" 
									   name="email" id="email" value="{{ old('email') }}">
								@error('email')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="col-md-6">
								<label class="form-label">Telefone <span class="text-danger">*</span></label>
								<input type="text" class="form-control @error('phone') is-invalid @enderror" 
									   name="phone" id="phone" value="{{ old('phone') }}" 
									   placeholder="+258 84 000 0000">
								@error('phone')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="col-md-6">
								<label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
								<select class="form-select @error('document_type') is-invalid @enderror" 
										name="document_type" id="document_type">
									<option value="">Selecione...</option>
									<option value="bi" {{ old('document_type') == 'bi' ? 'selected' : '' }}>BI</option>
									<option value="passport" {{ old('document_type') == 'passport' ? 'selected' : '' }}>Passaporte</option>
									<option value="birth_certificate" {{ old('document_type') == 'birth_certificate' ? 'selected' : '' }}>Certidão de Nascimento</option>
								</select>
								@error('document_type')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="col-md-6">
								<label class="form-label">Número do Documento <span class="text-danger">*</span></label>
								<input type="text" class="form-control @error('document_number') is-invalid @enderror" 
									   name="document_number" id="document_number" value="{{ old('document_number') }}">
								@error('document_number')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>
					</div>
				</div>

				<!-- Passo 4: Pagamento -->
				<div class="card mb-3 d-none" id="step4-card">
					<div class="card-header bg-warning text-dark">
						<h5 class="mb-0">
							<i class="ph-currency-circle-dollar me-2"></i>
							Passo 4: Pagamento
						</h5>
					</div>
					<div class="card-body">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">Método de Pagamento <span class="text-danger">*</span></label>
								<select class="form-select @error('payment_method') is-invalid @enderror" 
										name="payment_method" id="payment_method" 
										{{-- required --}}
										>
									<option value="">Selecione...</option>
									<option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Dinheiro</option>
									<option value="mpesa" {{ old('payment_method') == 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
									<option value="emola" {{ old('payment_method') == 'emola' ? 'selected' : '' }}>e-Mola</option>
								</select>
								@error('payment_method')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="col-md-6">
								<label class="form-label">Status <span class="text-danger">*</span></label>
								<select class="form-select @error('status') is-invalid @enderror" 
										name="status" id="status" 
										{{-- required --}}
										>
									<option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reservado</option>
									<option value="paid" {{ old('status', 'paid') == 'paid' ? 'selected' : '' }}>Pago</option>
								</select>
								@error('status')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="col-12">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="send_email" 
										   name="send_email" value="1" checked>
									<label class="form-check-label" for="send_email">
										Enviar email automaticamente para o passageiro
									</label>
								</div>
							</div>
							<div class="col-12">
								<label class="form-label">Notas/Observações (opcional)</label>
								<textarea class="form-control" name="notes" rows="3" 
										  placeholder="Observações sobre este bilhete...">{{ old('notes') }}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Resumo Lateral -->
			<div class="col-lg-4">
				<div class="card sticky-top" style="top: 20px;">
					<div class="card-header bg-light">
						<h5 class="mb-0">
							<i class="ph-receipt me-2"></i>
							Resumo do Bilhete
						</h5>
					</div>
					<div class="card-body">
						<div id="summary-content">
							<div class="text-center py-5">
								<i class="ph-ticket ph-3x text-muted mb-3 d-block"></i>
								<p class="text-muted">Selecione um horário para começar</p>
							</div>
						</div>
					</div>
					<div class="card-footer" id="summary-footer" style="display: none;">
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary btn-lg">
								<i class="ph-check-circle me-2"></i>
								Emitir Bilhete
							</button>
							<a href="{{ route('tickets.index') }}" class="btn btn-light">
								<i class="ph-x me-2"></i>
								Cancelar
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('styles')
<style>
/* Schedule Cards */
.schedule-card {
	border: 2px solid #dee2e6;
	border-radius: 8px;
	padding: 15px;
	margin-bottom: 10px;
	cursor: pointer;
	transition: all 0.3s;
}

.schedule-card:hover {
	border-color: #2196f3;
	box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
	transform: translateY(-2px);
}

.schedule-card.selected {
	border-color: #2196f3;
	background: #e3f2fd;
}

/* Seat Layout */
.seat-map {
	max-width: 400px;
	margin: 0 auto;
	padding: 20px;
	background: #f8f9fa;
	border-radius: 15px;
}

.seat {
	width: 45px;
	height: 50px;
	border-radius: 8px 8px 4px 4px;
	border: 2px solid;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	margin: 5px;
	cursor: pointer;
	transition: all 0.3s;
	font-weight: bold;
	font-size: 0.85rem;
}

.seat.available {
	background: #e8f5e9;
	border-color: #4caf50;
	color: #2e7d32;
}

.seat.available:hover {
	background: #c8e6c9;
	transform: scale(1.1);
}

.seat.selected {
	background: #2196f3;
	border-color: #1976d2;
	color: white;
	transform: scale(1.05);
}

.seat.occupied {
	background: #ffebee;
	border-color: #f44336;
	color: #c62828;
	cursor: not-allowed;
	opacity: 0.6;
}

.seat-legend {
	width: 30px;
	height: 30px;
	border-radius: 6px 6px 3px 3px;
	border: 2px solid;
	display: inline-block;
}

/* Passenger Card */
.passenger-card {
	border: 2px solid #dee2e6;
	border-radius: 8px;
	padding: 15px;
	cursor: pointer;
	transition: all 0.3s;
}

.passenger-card:hover {
	border-color: #2196f3;
	background: #f8f9fa;
}

.passenger-card.selected {
	border-color: #2196f3;
	background: #e3f2fd;
}

/* Summary Items */
.summary-item {
	display: flex;
	justify-content: space-between;
	padding: 10px 0;
	border-bottom: 1px solid #dee2e6;
}

.summary-item:last-child {
	border-bottom: none;
}

.summary-total {
	font-size: 1.5rem;
	font-weight: bold;
	color: #2196f3;
}
</style>
@endpush

@push('scripts')
<script>
let selectedSchedule = null;
let selectedSeat = null;
let selectedPassenger = null;
let schedulePrice = 0;

// Filtrar horários
function filterSchedules() {
	const date = document.getElementById('filter_date').value;
	const route = document.getElementById('filter_route').value;

	if (!date) {
		alert('Por favor, selecione uma data');
		return;
	}

	showLoading('schedules-list');

	fetch(`/api/schedules/available?date=${date}&route_id=${route}`)
		.then(response => response.json())
		.then(data => {
			displaySchedules(data);
		})
		.catch(error => {
			console.error('Erro:', error);
			document.getElementById('schedules-list').innerHTML = `
				<div class="alert alert-danger">
					<i class="ph-warning me-2"></i>
					Erro ao carregar horários. Tente novamente.
				</div>
			`;
		});
}

function displaySchedules(schedules) {
	const container = document.getElementById('schedules-list');

	if (schedules.length === 0) {
		container.innerHTML = `
			<div class="text-center py-4">
				<i class="ph-calendar-x ph-3x text-muted mb-2 d-block"></i>
				<p class="text-muted">Nenhum horário disponível para esta data</p>
			</div>
		`;
		return;
	}

	let html = '';
	schedules.forEach(schedule => {
        
        
        	// const price = parseFloat(schedule.price);
            // console.log(parseFloat(schedule.price));
		html += `
			<div class="schedule-card" onclick="selectSchedule(${schedule.id}, '${schedule.price}', '${schedule.route}', '${schedule.time}', '${schedule.bus}', ${schedule.available_seats})">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<div class="fw-bold">${schedule.route}</div>
						<small class="text-muted">
							<i class="ph-clock me-1"></i>${schedule.time} | 
							<i class="ph-bus me-1"></i>${schedule.bus}
						</small>
					</div>
					<div class="text-end">
						<div class="fw-bold text-success">${schedule.price} MT</div>
						<small class="badge ${schedule.available_seats > 0 ? 'bg-success' : 'bg-danger'}">
							${schedule.available_seats} lugares
						</small>
					</div>
				</div>
			</div>
		`;
	});

	container.innerHTML = html;
}

function selectSchedule(id, price, route, time, bus, availableSeats) {
    
	selectedSchedule = {id, price, route, time, bus, availableSeats};
	schedulePrice = price;
	 
	document.getElementById('schedule_id').value = id;
	
	// Highlight selected
	document.querySelectorAll('.schedule-card').forEach(card => {
		card.classList.remove('selected');
	});
	event.currentTarget.classList.add('selected');

	// Carregar assentos
	loadSeats(id);

	// Mostrar próximo passo
	document.getElementById('step2-card').style.display = 'block';
	document.getElementById('step2-card').scrollIntoView({behavior: 'smooth', block: 'start'});

	updateSummary();
}

function loadSeats(scheduleId) {
	showLoading('seat-selection-area');

	fetch(`/tickets/schedules/${scheduleId}/available-seats`)
		.then(response => response.json())
		.then(data => {
			displaySeats(data);
		})
		.catch(error => {
			console.error('Erro:', error);
			document.getElementById('seat-selection-area').innerHTML = `
				<div class="alert alert-danger">Erro ao carregar assentos</div>
			`;
		});
}

function displaySeats(data) {
	const container = document.getElementById('seat-selection-area');
	const {total_seats, occupied_seats} = data;

	// Legenda
	let html = `
		<div class="text-center mb-3">
			<div class="d-inline-flex gap-3">
				<div><span class="seat-legend available"></span> Disponível</div>
				<div><span class="seat-legend selected"></span> Selecionado</div>
				<div><span class="seat-legend occupied"></span> Ocupado</div>
			</div>
		</div>
		<div class="seat-map">
			<div class="text-center mb-3 pb-3 border-bottom">
				<i class="ph-steering-wheel ph-2x"></i>
				<div class="small text-muted">Motorista</div>
			</div>
	`;

	// Gerar assentos (4 por fila: 2-corredor-2)
	const seatsPerRow = 4;
	const rows = Math.ceil(total_seats / seatsPerRow);
	let seatNum = 1;

	for (let row = 0; row < rows; row++) {
		html += '<div class="text-center mb-2">';
		
		for (let col = 0; col < seatsPerRow && seatNum <= total_seats; col++) {
			if (col === 2) {
				html += '<span style="display:inline-block;width:30px;"></span>'; // Corredor
			}
			
			const isOccupied = occupied_seats.includes(seatNum.toString());
			const status = isOccupied ? 'occupied' : 'available';
			
			html += `
				<div class="seat ${status}" ${!isOccupied ? `onclick="selectSeat('${seatNum}')"` : ''}>
					${seatNum}
				</div>
			`;
			seatNum++;
		}
		
		html += '</div>';
	}

	html += '</div>';
	container.innerHTML = html;
}

function selectSeat(seatNumber) {
	// Remover seleção anterior
	document.querySelectorAll('.seat.selected').forEach(seat => {
		seat.classList.remove('selected');
		seat.classList.add('available');
	});

	// Selecionar novo
	event.currentTarget.classList.remove('available');
	event.currentTarget.classList.add('selected');
	
	selectedSeat = seatNumber;
	document.getElementById('seat_number').value = seatNumber;

	// Mostrar próximo passo
	document.getElementById('step3-card').style.display = 'block';
	document.getElementById('step3-card').scrollIntoView({behavior: 'smooth', block: 'start'});

	updateSummary();
}

// Buscar passageiro
function searchPassenger() {
	const search = document.getElementById('passenger_search').value;

	if (!search || search.length < 3) {
		alert('Digite pelo menos 3 caracteres');
		return;
	}

	showLoading('passenger-search-results');

	fetch(`/api/passengers/search?q=${encodeURIComponent(search)}`)
		.then(response => response.json())
		.then(data => {
			displayPassengers(data);
		})
		.catch(error => {
			console.error('Erro:', error);
			document.getElementById('passenger-search-results').innerHTML = '';
		});
}

function displayPassengers(passengers) {
	const container = document.getElementById('passenger-search-results');

	if (passengers.length === 0) {
		container.innerHTML = `
			<div class="alert alert-warning">
				<i class="ph-magnifying-glass me-2"></i>
				Nenhum passageiro encontrado. Crie um novo abaixo.
			</div>
		`;
		return;
	}

	let html = '<div class="mb-3"><strong>Passageiros Encontrados:</strong></div>';
	
	passengers.forEach(passenger => {
		html += `
			<div class="passenger-card mb-2" onclick="selectPassenger(${passenger.id}, '${passenger.first_name}', '${passenger.last_name}', '${passenger.email}', '${passenger.phone}')">
				<div class="d-flex justify-content-between">
					<div>
						<div class="fw-bold">${passenger.first_name} ${passenger.last_name}</div>
						<small class="text-muted">
							${passenger.email} | ${passenger.phone}
						</small>
					</div>
					<div>
						<i class="ph-check-circle ph-2x text-success"></i>
					</div>
				</div>
			</div>
		`;
	});

	container.innerHTML = html;
}

function selectPassenger(id, firstName, lastName, email, phone) {
	selectedPassenger = {id, firstName, lastName, email, phone};
	
	document.getElementById('passenger_id').value = id;
	
	// Highlight
	document.querySelectorAll('.passenger-card').forEach(card => {
		card.classList.remove('selected');
	});
	event.currentTarget.classList.add('selected');

	// Limpar formulário novo passageiro
	document.getElementById('first_name').value = '';
	document.getElementById('last_name').value = '';
	document.getElementById('email').value = '';
	document.getElementById('phone').value = '';
	document.getElementById('document_type').value = '';
	document.getElementById('document_number').value = '';

	// Mostrar próximo passo
	// document.getElementById('step4-card').style.display = 'block';
	// document.getElementById('step4-card').scrollIntoView({behavior: 'smooth', block: 'start'});
	// MOSTRA O PASSO 4
    // document.getElementById('step4-card').style.display = 'block';
    // document.getElementById('step4-card').scrollIntoView({behavior: 'smooth', block: 'start'});

    // // AQUI É O PULO DO GATO: adiciona o required só agora que o campo está visível
    // document.getElementById('payment_method').setAttribute('required', 'required');
    // document.getElementById('status').setAttribute('required', 'required');
showPaymentStep();
	updateSummary();
}

// Função nova que detecta quando o usuário terminou de preencher novo passageiro
function checkAndShowPayment() {
    const fields = ['first_name', 'last_name', 'phone', 'document_type', 'document_number'];
    const allFilled = fields.every(field => {
        const value = document.getElementById(field).value.trim();
        return value !== '';
    });

    const hasPassenger = document.getElementById('passenger_id').value !== '';

    if (allFilled || hasPassenger) {
        showPaymentStep();
    }
}
function showPaymentStep() {
    const step4 = document.getElementById('step4-card');
    if (step4.classList.contains('d-none')) {
        step4.classList.remove('d-none');
        step4.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
// Atualizar resumo
function updateSummary() {
	const summaryContent = document.getElementById('summary-content');
	const summaryFooter = document.getElementById('summary-footer');
	
	if (!selectedSchedule) {
		return;
	}

	let html = '<div class="summary-items">';

	// Horário
	if (selectedSchedule) {
		html += `
			<div class="summary-item">
				<span><i class="ph-calendar me-2"></i>Horário:</span>
				<span class="fw-bold">${selectedSchedule.time}</span>
			</div>
			<div class="summary-item">
				<span><i class="ph-map-trifold me-2"></i>Rota:</span>
				<span class="small">${selectedSchedule.route}</span>
			</div>
		`;
	}

	// Assento
	if (selectedSeat) {
		html += `
			<div class="summary-item">
				<span><i class="ph-armchair me-2"></i>Assento:</span>
				<span class="fw-bold">${selectedSeat}</span>
			</div>
		`;
	}

	// Passageiro
	if (selectedPassenger) {
		html += `
			<div class="summary-item">
				<span><i class="ph-user me-2"></i>Passageiro:</span>
				<span class="small">${selectedPassenger.firstName} ${selectedPassenger.lastName}</span>
			</div>
		`;
	}

	// Preço
	if (schedulePrice) {
		html += `
			<div class="summary-item mt-3 pt-3 border-top">
				<span class="fs-5">Total:</span>
				<span class="summary-total">${schedulePrice} MT</span>
			</div>
		`;

		// Campo hidden com preço
		document.querySelector('input[name="price"]')?.remove();
		const priceInput = document.createElement('input');
		priceInput.type = 'hidden';
		priceInput.name = 'price';
		priceInput.value = parseFloat(schedulePrice.toString().replace(/[^0-9.-]+/g, ''));
		document.getElementById('ticketForm').appendChild(priceInput);
	}

	html += '</div>';
	summaryContent.innerHTML = html;
	
	// Mostrar botão de submit
	if (selectedSchedule && selectedSeat) {
		summaryFooter.style.display = 'block';
	}
}

// Loading helper
function showLoading(elementId) {
	document.getElementById(elementId).innerHTML = `
		<div class="text-center py-4">
			<div class="spinner-border text-primary" role="status">
				<span class="visually-hidden">Carregando...</span>
			</div>
		</div>
	`;
}

// Ao carregar página, se tiver schedule_id pré-selecionado
document.addEventListener('DOMContentLoaded', function() {
	const scheduleId = document.getElementById('schedule_id').value;
	const seatNumber = document.getElementById('seat_number').value;

	if (scheduleId) {
		// TODO: Carregar dados do schedule e mostrar passos
	}
});

// Monitora preenchimento manual do novo passageiro
document.querySelectorAll('#first_name, #last_name, #phone, #document_type, #document_number').forEach(input => {
    input.addEventListener('input', checkAndShowPayment);
    input.addEventListener('change', checkAndShowPayment);
});

// Também verifica ao colar
document.querySelectorAll('#first_name, #last_name, #phone, #document_type, #document_number').forEach(input => {
    input.addEventListener('paste', () => setTimeout(checkAndShowPayment, 100));
});
</script>
@endpush
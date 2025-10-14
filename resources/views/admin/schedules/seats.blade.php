@extends('layouts.app')

@section('title', 'Mapa de Assentos')

@section('page-title')
	Horários - <span class="fw-normal">Mapa de Assentos</span>
@endsection

@section('breadcrumbs')
	<a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
	<a href="{{ route('schedules.index') }}" class="breadcrumb-item">Horários</a>
	<a href="{{ route('schedules.show', $schedule) }}" class="breadcrumb-item">Detalhes</a>
	<span class="breadcrumb-item active">Mapa de Assentos</span>
@endsection

@section('header-actions')
	<a href="{{ route('schedules.show', $schedule) }}" class="btn btn-light">
		<i class="ph-arrow-left me-2"></i>
		Voltar
	</a>
@endsection

@section('content')

<!-- Informação da Viagem -->
<div class="card mb-3">
	<div class="card-body">
		<div class="row align-items-center">
			<div class="col-md-6">
				<h5 class="mb-2">
					<i class="ph-map-pin text-primary me-2"></i>
					{{ $schedule->route->originCity->name }} 
					<i class="ph-arrow-right mx-2"></i> 
					{{ $schedule->route->destinationCity->name }}
				</h5>
				<div class="text-muted">
					<i class="ph-calendar me-1"></i>
					{{ $schedule->departure_date->format('d/m/Y') }} às {{ $schedule->departure_time }}
					<span class="mx-2">|</span>
					<i class="ph-bus me-1"></i>
					{{ $schedule->bus->registration_number }} - {{ $schedule->bus->model }}
				</div>
			</div>
			<div class="col-md-6 text-md-end">
				@php
					$totalSeats = $schedule->bus->total_seats;
					$occupiedCount = count($occupiedSeats);
					$availableCount = $totalSeats - $occupiedCount;
					$occupancyRate = $totalSeats > 0 ? ($occupiedCount / $totalSeats) * 100 : 0;
				@endphp
				<div class="d-inline-block me-3">
					<div class="fw-bold fs-3">{{ $occupiedCount }}/{{ $totalSeats }}</div>
					<small class="text-muted">Lugares Ocupados</small>
				</div>
				<div class="d-inline-block">
					<div class="fw-bold fs-3 text-success">{{ $availableCount }}</div>
					<small class="text-muted">Disponíveis</small>
				</div>
			</div>
		</div>
		<div class="progress mt-3" style="height: 8px;">
			@php
				$color = $occupancyRate >= 80 ? 'danger' : ($occupancyRate >= 50 ? 'warning' : 'success');
			@endphp
			<div class="progress-bar bg-{{ $color }}" style="width: {{ $occupancyRate }}%"></div>
		</div>
	</div>
</div>

<div class="row">
	<!-- Mapa de Assentos -->
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="ph-armchair me-2"></i>
					Mapa de Assentos do Autocarro
				</h5>
			</div>
			<div class="card-body">
				<!-- Legenda -->
				<div class="d-flex justify-content-center gap-4 mb-4 pb-3 border-bottom">
					<div class="d-flex align-items-center">
						<div class="seat-preview available me-2"></div>
						<span class="small">Disponível</span>
					</div>
					<div class="d-flex align-items-center">
						<div class="seat-preview occupied me-2"></div>
						<span class="small">Ocupado</span>
					</div>
					<div class="d-flex align-items-center">
						<div class="seat-preview selected me-2"></div>
						<span class="small">Selecionado</span>
					</div>
				</div>

				<!-- Bus Layout -->
				<div class="bus-layout">
					<!-- Driver Area -->
					<div class="driver-area mb-4">
						<div class="driver-seat">
							<i class="ph-steering-wheel ph-2x"></i>
							<div class="small mt-1">Motorista</div>
						</div>
					</div>

					<!-- Seats Grid -->
					<div class="seats-container">
						@foreach($seatLayout as $rowIndex => $row)
							<div class="seat-row">
								<div class="row-number">{{ $rowIndex + 1 }}</div>
								<div class="seats-grid">
									@foreach($row as $seat)
										@php
											$isOccupied = in_array($seat['number'], $occupiedSeats);
											$seatClass = $isOccupied ? 'occupied' : 'available';
											$seatIcon = $isOccupied ? 'ph-user' : 'ph-armchair';
										@endphp
										<div class="seat {{ $seatClass }} {{ $seat['position'] }}" 
											 data-seat="{{ $seat['number'] }}"
											 data-occupied="{{ $isOccupied ? 'true' : 'false' }}"
											 onclick="selectSeat(this)">
											<i class="{{ $seatIcon }}"></i>
											<div class="seat-number">{{ $seat['number'] }}</div>
										</div>
										
										<!-- Corredor entre assentos -->
										@if($seat['position'] === 'left')
											<div class="aisle"></div>
										@endif
									@endforeach
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Informações do Assento Selecionado -->
	<div class="col-lg-4">
		<div class="card" id="seat-info-card" style="display: none;">
			<div class="card-header bg-primary text-white">
				<h6 class="mb-0">
					<i class="ph-info me-2"></i>
					Informações do Assento
				</h6>
			</div>
			<div class="card-body">
				<div class="text-center mb-3">
					<div class="seat-preview-large selected mb-2">
						<i class="ph-armchair ph-3x"></i>
					</div>
					<h4 class="mb-0">Assento <span id="selected-seat-number">-</span></h4>
				</div>

				<div id="seat-available-info">
					<div class="alert alert-success">
						<i class="ph-check-circle me-2"></i>
						Este assento está disponível
					</div>
					
					<div class="d-grid gap-2">
						<button type="button" class="btn btn-success" onclick="sellTicket()">
							<i class="ph-ticket me-2"></i>
							Vender Bilhete
						</button>
						<button type="button" class="btn btn-outline-primary" onclick="reserveSeat()">
							<i class="ph-clock me-2"></i>
							Reservar Temporariamente
						</button>
					</div>
				</div>

				<div id="seat-occupied-info" style="display: none;">
					<div class="alert alert-warning">
						<i class="ph-warning me-2"></i>
						Este assento está ocupado
					</div>

					<div class="mb-3">
						<label class="small text-muted d-block">Passageiro</label>
						<div class="fw-semibold" id="passenger-name">Carregando...</div>
					</div>

					<div class="mb-3">
						<label class="small text-muted d-block">Nº Bilhete</label>
						<div class="fw-semibold" id="ticket-number">-</div>
					</div>

					<div class="mb-3">
						<label class="small text-muted d-block">Status</label>
						<span id="ticket-status" class="badge">-</span>
					</div>

					<div class="d-grid gap-2">
						<a href="#" id="view-ticket-link" class="btn btn-outline-primary">
							<i class="ph-eye me-2"></i>
							Ver Bilhete
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Estatísticas -->
		<div class="card mt-3">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="ph-chart-bar me-2"></i>
					Resumo
				</h6>
			</div>
			<div class="card-body">
				<div class="d-flex justify-content-between mb-3">
					<span class="text-muted">Total de Assentos</span>
					<span class="fw-semibold">{{ $totalSeats }}</span>
				</div>
				<div class="d-flex justify-content-between mb-3">
					<span class="text-muted">
						<i class="ph-user text-danger me-1"></i>
						Ocupados
					</span>
					<span class="fw-semibold">{{ $occupiedCount }}</span>
				</div>
				<div class="d-flex justify-content-between mb-3">
					<span class="text-muted">
						<i class="ph-check text-success me-1"></i>
						Disponíveis
					</span>
					<span class="fw-semibold text-success">{{ $availableCount }}</span>
				</div>
				<hr>
				<div class="d-flex justify-content-between">
					<span class="text-muted">Taxa de Ocupação</span>
					<span class="fw-semibold">{{ number_format($occupancyRate, 1) }}%</span>
				</div>
			</div>
		</div>

		<!-- Ações Rápidas -->
		@if($schedule->status === 'active')
			<div class="card mt-3">
				<div class="card-header">
					<h6 class="mb-0">
						<i class="ph-lightning me-2"></i>
						Ações Rápidas
					</h6>
				</div>
				<div class="card-body">
					<div class="d-grid gap-2">
						<a href="{{ route('tickets.create', ['schedule_id' => $schedule->id]) }}" 
						   class="btn btn-primary">
							<i class="ph-ticket me-2"></i>
							Vender Bilhete
						</a>
						<a href="{{ route('schedules.show', $schedule) }}" 
						   class="btn btn-outline-secondary">
							<i class="ph-arrow-left me-2"></i>
							Voltar aos Detalhes
						</a>
					</div>
				</div>
			</div>
		@endif
	</div>
</div>

@endsection

@push('styles')
<style>
/* Seat Preview */
.seat-preview {
	width: 30px;
	height: 30px;
	border-radius: 8px 8px 0 0;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	border: 2px solid;
}

.seat-preview.available {
	background: #e8f5e9;
	border-color: #4caf50;
	color: #4caf50;
}

.seat-preview.occupied {
	background: #ffebee;
	border-color: #f44336;
	color: #f44336;
}

.seat-preview.selected {
	background: #e3f2fd;
	border-color: #2196f3;
	color: #2196f3;
}

.seat-preview-large {
	width: 80px;
	height: 80px;
	border-radius: 12px 12px 0 0;
	display: flex;
	align-items: center;
	justify-content: center;
	border: 3px solid;
	margin: 0 auto;
}

/* Bus Layout */
.bus-layout {
	max-width: 500px;
	margin: 0 auto;
	padding: 20px;
	background: #f8f9fa;
	border-radius: 20px;
	border: 3px solid #dee2e6;
}

.driver-area {
	text-align: center;
	padding: 15px;
	background: white;
	border-radius: 10px;
	border: 2px dashed #dee2e6;
}

.driver-seat {
	display: inline-block;
	padding: 10px 20px;
	background: #e9ecef;
	border-radius: 8px;
	color: #495057;
}

.seats-container {
	background: white;
	padding: 20px;
	border-radius: 15px;
}

.seat-row {
	display: flex;
	align-items: center;
	margin-bottom: 15px;
}

.row-number {
	width: 30px;
	text-align: center;
	font-weight: bold;
	color: #6c757d;
	font-size: 0.9rem;
}

.seats-grid {
	display: flex;
	gap: 10px;
	flex: 1;
}

.aisle {
	width: 40px;
	border-left: 2px dashed #dee2e6;
	margin: 0 5px;
}

/* Seat Styles */
.seat {
	width: 50px;
	height: 60px;
	border-radius: 10px 10px 0 0;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	border: 2px solid;
	cursor: pointer;
	transition: all 0.3s ease;
	position: relative;
}

.seat i {
	font-size: 1.5rem;
	margin-bottom: 2px;
}

.seat-number {
	font-size: 0.75rem;
	font-weight: bold;
}

.seat.available {
	background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
	border-color: #4caf50;
	color: #2e7d32;
}

.seat.available:hover {
	background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%);
	transform: translateY(-3px);
	box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.seat.occupied {
	background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
	border-color: #f44336;
	color: #c62828;
	cursor: not-allowed;
}

.seat.selected {
	background: linear-gradient(135deg, #e3f2fd 0%, #90caf9 100%);
	border-color: #2196f3;
	color: #1565c0;
	transform: translateY(-3px);
	box-shadow: 0 6px 16px rgba(33, 150, 243, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
	.bus-layout {
		max-width: 100%;
		padding: 15px;
	}

	.seat {
		width: 40px;
		height: 50px;
	}

	.seat i {
		font-size: 1.2rem;
	}

	.aisle {
		width: 25px;
	}
}
</style>
@endpush

@push('scripts')
<script>
let selectedSeatNumber = null;

function selectSeat(seatElement) {
	const seatNumber = seatElement.dataset.seat;
	const isOccupied = seatElement.dataset.occupied === 'true';

	// Remover seleção anterior
	document.querySelectorAll('.seat.selected').forEach(seat => {
		if (!seat.classList.contains('occupied')) {
			seat.classList.remove('selected');
			seat.classList.add('available');
		}
	});

	// Selecionar novo assento
	if (!isOccupied) {
		seatElement.classList.remove('available');
		seatElement.classList.add('selected');
	}

	selectedSeatNumber = seatNumber;

	// Mostrar informações
	showSeatInfo(seatNumber, isOccupied);
}

function showSeatInfo(seatNumber, isOccupied) {
	const infoCard = document.getElementById('seat-info-card');
	const availableInfo = document.getElementById('seat-available-info');
	const occupiedInfo = document.getElementById('seat-occupied-info');
	
	document.getElementById('selected-seat-number').textContent = seatNumber;
	
	if (isOccupied) {
		availableInfo.style.display = 'none';
		occupiedInfo.style.display = 'block';
		loadTicketInfo(seatNumber);
	} else {
		availableInfo.style.display = 'block';
		occupiedInfo.style.display = 'none';
	}
	
	infoCard.style.display = 'block';
}

async function loadTicketInfo(seatNumber) {
	try {
		// TODO: Implementar chamada à API para buscar informações do bilhete
		// Por enquanto, mostrar dados simulados
		document.getElementById('passenger-name').textContent = 'Carregando...';
		document.getElementById('ticket-number').textContent = 'Carregando...';
		
		// Simulação de delay de carregamento
		await new Promise(resolve => setTimeout(resolve, 500));
		
		// Dados simulados - substituir por chamada real à API
		document.getElementById('passenger-name').textContent = 'João Silva';
		document.getElementById('ticket-number').textContent = 'TKT-20250114-ABC123';
		document.getElementById('ticket-status').innerHTML = '<i class="ph-check-circle me-1"></i> Pago';
		document.getElementById('ticket-status').className = 'badge bg-success';
		document.getElementById('view-ticket-link').href = '#'; // URL do bilhete
	} catch (error) {
		console.error('Erro ao carregar informações do bilhete:', error);
		document.getElementById('passenger-name').textContent = 'Erro ao carregar';
	}
}

function sellTicket() {
	if (!selectedSeatNumber) {
		alert('Por favor, selecione um assento primeiro.');
		return;
	}
	
	// Redirecionar para página de venda de bilhete com assento pré-selecionado
	window.location.href = `{{ route('tickets.create') }}?schedule_id={{ $schedule->id }}&seat=${selectedSeatNumber}`;
}

function reserveSeat() {
	if (!selectedSeatNumber) {
		alert('Por favor, selecione um assento primeiro.');
		return;
	}
	
	// TODO: Implementar reserva temporária de assento
	alert(`Funcionalidade de reserva temporária para o assento ${selectedSeatNumber} será implementada em breve.`);
}

// Auto-selecionar primeiro assento disponível ao carregar
document.addEventListener('DOMContentLoaded', function() {
	const firstAvailable = document.querySelector('.seat.available');
	if (firstAvailable) {
		// Não selecionar automaticamente, apenas mostrar disponibilidade
	}
});
</script>
@endpush
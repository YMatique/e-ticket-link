@extends('layouts.app')

@section('content')
<div class="content">
	<!-- Page Header -->
	<div class="page-header page-header-light shadow mb-4">
		<div class="page-header-content d-flex">
			<div class="page-title">
				<h4>
					<a href="{{ route('tickets.index') }}"><i class="ph-arrow-left me-2"></i></a>
					<span class="fw-semibold">Validar Bilhete</span>
				</h4>
			</div>
		</div>
	</div>
	<!-- /page header -->

	<div class="row">
		<!-- Scanner Principal -->
		<div class="col-lg-8">
			<!-- Tabs -->
			<ul class="nav nav-tabs nav-tabs-highlight mb-3" role="tablist">
				<li class="nav-item" role="presentation">
					<a href="#scanner-tab" class="nav-link active" data-bs-toggle="tab" role="tab">
						<i class="ph-camera me-2"></i>
						Scanner QR Code
					</a>
				</li>
				<li class="nav-item" role="presentation">
					<a href="#manual-tab" class="nav-link" data-bs-toggle="tab" role="tab">
						<i class="ph-keyboard me-2"></i>
						Validação Manual
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<!-- Tab 1: Scanner QR Code -->
				<div class="tab-pane fade show active" id="scanner-tab" role="tabpanel">
					<div class="card">
						<div class="card-header bg-primary text-white">
							<h5 class="mb-0">
								<i class="ph-qr-code me-2"></i>
								Scanner de QR Code
							</h5>
						</div>
						<div class="card-body">
							<!-- Seletor de Câmera -->
							<div class="mb-3">
								<label class="form-label">Selecionar Câmera</label>
								<select class="form-select" id="camera-select">
									<option value="">Carregando câmeras...</option>
								</select>
							</div>

							<!-- Área do Scanner -->
							<div class="scanner-container">
								<div id="qr-reader" style="width: 100%;"></div>
								<div id="scanner-overlay" class="text-center py-5">
									<i class="ph-qr-code ph-4x text-muted mb-3 d-block"></i>
									<p class="text-muted">Posicione o QR Code na frente da câmera</p>
									<button type="button" class="btn btn-primary" id="start-scanner">
										<i class="ph-camera me-2"></i>
										Iniciar Scanner
									</button>
								</div>
							</div>

							<!-- Instruções -->
							<div class="alert alert-info mt-3">
								<i class="ph-info me-2"></i>
								<strong>Instruções:</strong>
								<ul class="mb-0 mt-2">
									<li>Posicione o QR Code do bilhete na frente da câmera</li>
									<li>Mantenha o código estável para leitura</li>
									<li>Certifique-se de ter boa iluminação</li>
									<li>O sistema validará automaticamente após leitura</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<!-- Tab 2: Validação Manual -->
				<div class="tab-pane fade" id="manual-tab" role="tabpanel">
					<div class="card">
						<div class="card-header bg-success text-white">
							<h5 class="mb-0">
								<i class="ph-keyboard me-2"></i>
								Validação Manual
							</h5>
						</div>
						<div class="card-body">
							<form id="manual-validation-form">
								<div class="mb-3">
									<label class="form-label">Número do Bilhete <span class="text-danger">*</span></label>
									<input type="text" class="form-control form-control-lg" 
										   id="ticket-number-input" 
										   placeholder="TKT-YYYYMMDD-XXXXXX"
										   autocomplete="off"
										   autofocus>
									<small class="text-muted">Digite ou cole o número do bilhete</small>
								</div>

								<div class="d-grid">
									<button type="submit" class="btn btn-success btn-lg">
										<i class="ph-check-circle me-2"></i>
										Validar Bilhete
									</button>
								</div>
							</form>

							<div class="alert alert-info mt-3">
								<i class="ph-lightbulb me-2"></i>
								<strong>Dica:</strong> Use este método quando o QR Code estiver danificado ou ilegível.
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Resultado da Validação -->
			<div id="validation-result" class="mt-3" style="display: none;"></div>
		</div>

		<!-- Painel Lateral -->
		<div class="col-lg-4">
			<!-- Card de Estatísticas -->
			<div class="card mb-3">
				<div class="card-header bg-light">
					<h5 class="mb-0">
						<i class="ph-chart-bar me-2"></i>
						Estatísticas de Hoje
					</h5>
				</div>
				<div class="card-body">
					<div class="mb-3 pb-3 border-bottom">
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-muted">Total Validados:</span>
							<span class="h4 mb-0 text-success" id="stats-validated">0</span>
						</div>
					</div>
					<div class="mb-3 pb-3 border-bottom">
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-muted">Tentativas Falhadas:</span>
							<span class="h4 mb-0 text-danger" id="stats-failed">0</span>
						</div>
					</div>
					<div>
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-muted">Taxa de Sucesso:</span>
							<span class="h4 mb-0 text-primary" id="stats-rate">0%</span>
						</div>
					</div>
				</div>
			</div>

			<!-- Histórico Recente -->
			<div class="card">
				<div class="card-header bg-light">
					<h5 class="mb-0">
						<i class="ph-clock-counter-clockwise me-2"></i>
						Últimas Validações
					</h5>
				</div>
				<div class="card-body p-0">
					<div id="recent-validations" class="list-group list-group-flush">
						<div class="list-group-item text-center text-muted py-4">
							<i class="ph-clock-counter-clockwise ph-2x mb-2 d-block"></i>
							Nenhuma validação hoje
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
/* Scanner Container */
.scanner-container {
	position: relative;
	background: #f8f9fa;
	border-radius: 8px;
	overflow: hidden;
	min-height: 400px;
}

#qr-reader {
	display: none;
}

#qr-reader.active {
	display: block;
}

#scanner-overlay {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: #f8f9fa;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	z-index: 1;
}

#scanner-overlay.hidden {
	display: none;
}

/* QR Scanner Styling */
#qr-reader video {
	border-radius: 8px;
	width: 100% !important;
	height: auto !important;
}

#qr-reader__dashboard {
	display: none !important;
}

/* Validation Result Cards */
.validation-success {
	animation: slideInDown 0.5s ease-out;
}

.validation-error {
	animation: shake 0.5s ease-out;
}

@keyframes slideInDown {
	from {
		opacity: 0;
		transform: translateY(-20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

@keyframes shake {
	0%, 100% { transform: translateX(0); }
	10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
	20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Ticket Info Display */
.ticket-info-row {
	display: flex;
	justify-content: space-between;
	padding: 10px 0;
	border-bottom: 1px solid #dee2e6;
}

.ticket-info-row:last-child {
	border-bottom: none;
}

/* Recent Validations */
.validation-item {
	transition: background 0.3s;
}

.validation-item:hover {
	background: #f8f9fa;
}

.validation-time {
	font-size: 0.75rem;
	color: #6c757d;
}

/* Camera Selection */
#camera-select {
	max-width: 300px;
}

/* Responsive */
@media (max-width: 991px) {
	.scanner-container {
		min-height: 300px;
	}
}
</style>
@endpush

@push('scripts')
<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner = null;
let isScanning = false;
let validatedCount = 0;
let failedCount = 0;
let recentValidations = [];

// Inicializar ao carregar página
document.addEventListener('DOMContentLoaded', function() {
	loadCameras();
	loadTodayStats();
	setupManualValidation();
	
	// Auto-refresh stats a cada 30 segundos
	setInterval(loadTodayStats, 30000);
});

// Carregar câmeras disponíveis
function loadCameras() {
	Html5Qrcode.getCameras().then(cameras => {
		const select = document.getElementById('camera-select');
		
		if (cameras && cameras.length) {
			select.innerHTML = '<option value="">Selecione uma câmera</option>';
			
			cameras.forEach((camera, index) => {
				const option = document.createElement('option');
				option.value = camera.id;
				option.text = camera.label || `Câmera ${index + 1}`;
				select.appendChild(option);
			});
			
			// Auto-selecionar primeira câmera traseira (se disponível)
			const rearCamera = cameras.find(c => c.label.toLowerCase().includes('back') || c.label.toLowerCase().includes('rear'));
			if (rearCamera) {
				select.value = rearCamera.id;
			} else if (cameras.length > 0) {
				select.value = cameras[0].id;
			}
		} else {
			select.innerHTML = '<option value="">Nenhuma câmera disponível</option>';
			showToast('Nenhuma câmera encontrada', 'warning');
		}
	}).catch(err => {
		console.error('Erro ao carregar câmeras:', err);
		document.getElementById('camera-select').innerHTML = '<option value="">Erro ao carregar câmeras</option>';
		showToast('Erro ao acessar câmeras: ' + err, 'danger');
	});
}

// Iniciar scanner
document.getElementById('start-scanner').addEventListener('click', function() {
	const cameraId = document.getElementById('camera-select').value;
	
	if (!cameraId) {
		showToast('Por favor, selecione uma câmera', 'warning');
		return;
	}
	
	startScanning(cameraId);
});

function startScanning(cameraId) {
	if (isScanning) {
		return;
	}
	
	const config = {
		fps: 10,
		qrbox: { width: 350, height: 350 },
		aspectRatio: 1.0
	};
	
	html5QrcodeScanner = new Html5Qrcode("qr-reader");
	
	html5QrcodeScanner.start(
		cameraId,
		config,
		onScanSuccess,
		onScanFailure
	).then(() => {
		isScanning = true;
		document.getElementById('qr-reader').classList.add('active');
		document.getElementById('scanner-overlay').classList.add('hidden');
		showToast('Scanner iniciado com sucesso', 'success');
	}).catch(err => {
		console.error('Erro ao iniciar scanner:', err);
		showToast('Erro ao iniciar scanner: ' + err, 'danger');
	});
}

function stopScanning() {
	if (!isScanning || !html5QrcodeScanner) {
		return;
	}
	
	html5QrcodeScanner.stop().then(() => {
		isScanning = false;
		document.getElementById('qr-reader').classList.remove('active');
		document.getElementById('scanner-overlay').classList.remove('hidden');
	}).catch(err => {
		console.error('Erro ao parar scanner:', err);
	});
}

// Callback quando QR Code é lido
function onScanSuccess(decodedText, decodedResult) {
	console.log('QR Code lido:', decodedText);
	
	// Parar scanner temporariamente
	stopScanning();
	
	// Validar bilhete
	validateTicket(decodedText);
}

function onScanFailure(error) {
	// Ignora erros de scan (muito verboso)
}

// Validar bilhete via API
function validateTicket(qrCodeData) {
	// Extrair ticket_number do QR Code
	// Formato: base64(TICKET|TIMESTAMP|HASH)
	let ticketNumber;
	
	try {
		// Tentar decodificar Base64
		const decoded = atob(qrCodeData);
		const parts = decoded.split('|');
		ticketNumber = parts[0]; // TKT-YYYYMMDD-XXXXXX
	} catch (e) {
		// Se falhar, assumir que é o número direto
		ticketNumber = qrCodeData;
	}
	
	console.log('Validando bilhete:', ticketNumber);
	
	// Mostrar loading
	showValidationLoading();
	
	// Buscar bilhete
	fetch(`/tickets/${ticketNumber}/find`, {
		method: 'GET',
		headers: {
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
			'Accept': 'application/json',
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.success && data.ticket) {
			// Validar o bilhete
			return fetch(`/tickets/${data.ticket.id}/validate`, {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
					'Content-Type': 'application/json',
					'Accept': 'application/json',
				}
			});
		} else {
			throw new Error(data.message || 'Bilhete não encontrado');
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			showValidationSuccess(data.ticket);
			playSuccessSound();
			validatedCount++;
			addToRecentValidations(data.ticket, true);
			loadTodayStats();
			
			// Reiniciar scanner após 3 segundos
			setTimeout(() => {
				hideValidationResult();
				const cameraId = document.getElementById('camera-select').value;
				if (cameraId) {
					startScanning(cameraId);
				}
			}, 3000);
		} else {
			throw new Error(data.message || 'Erro ao validar bilhete');
		}
	})
	.catch(error => {
		console.error('Erro:', error);
		showValidationError(error.message);
		playErrorSound();
		failedCount++;
		addToRecentValidations({ ticket_number: ticketNumber, error: error.message }, false);
		loadTodayStats();
		
		// Reiniciar scanner após 3 segundos
		setTimeout(() => {
			hideValidationResult();
			const cameraId = document.getElementById('camera-select').value;
			if (cameraId) {
				startScanning(cameraId);
			}
		}, 3000);
	});
}

// Validação manual
function setupManualValidation() {
	document.getElementById('manual-validation-form').addEventListener('submit', function(e) {
		e.preventDefault();
		
		const ticketNumber = document.getElementById('ticket-number-input').value.trim();
		
		if (!ticketNumber) {
			showToast('Digite o número do bilhete', 'warning');
			return;
		}
		
		validateTicket(ticketNumber);
	});
}

// Mostrar resultado de sucesso
function showValidationSuccess(ticket) {
	const resultDiv = document.getElementById('validation-result');
	
	resultDiv.innerHTML = `
		<div class="card border-success validation-success">
			<div class="card-header bg-success text-white">
				<h5 class="mb-0">
					<i class="ph-check-circle me-2"></i>
					Bilhete Validado com Sucesso!
				</h5>
			</div>
			<div class="card-body">
				<div class="ticket-info-row">
					<span class="text-muted">Número do Bilhete:</span>
					<span class="fw-bold">${ticket.ticket_number}</span>
				</div>
				<div class="ticket-info-row">
					<span class="text-muted">Passageiro:</span>
					<span class="fw-bold">${ticket.passenger_name}</span>
				</div>
				<div class="ticket-info-row">
					<span class="text-muted">Assento:</span>
					<span class="badge bg-secondary">${ticket.seat_number}</span>
				</div>
				<div class="ticket-info-row">
					<span class="text-muted">Rota:</span>
					<span>${ticket.route}</span>
				</div>
				<div class="ticket-info-row">
					<span class="text-muted">Validado em:</span>
					<span class="text-success fw-bold">${new Date().toLocaleString('pt-MZ')}</span>
				</div>
			</div>
		</div>
	`;
	
	resultDiv.style.display = 'block';
	resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Mostrar resultado de erro
function showValidationError(message) {
	const resultDiv = document.getElementById('validation-result');
	
	resultDiv.innerHTML = `
		<div class="card border-danger validation-error">
			<div class="card-header bg-danger text-white">
				<h5 class="mb-0">
					<i class="ph-x-circle me-2"></i>
					Falha na Validação
				</h5>
			</div>
			<div class="card-body">
				<div class="alert alert-danger mb-0">
					<i class="ph-warning me-2"></i>
					<strong>Erro:</strong> ${message}
				</div>
			</div>
		</div>
	`;
	
	resultDiv.style.display = 'block';
	resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Mostrar loading
function showValidationLoading() {
	const resultDiv = document.getElementById('validation-result');
	
	resultDiv.innerHTML = `
		<div class="card">
			<div class="card-body text-center py-4">
				<div class="spinner-border text-primary mb-3" role="status">
					<span class="visually-hidden">Validando...</span>
				</div>
				<p class="text-muted mb-0">Validando bilhete...</p>
			</div>
		</div>
	`;
	
	resultDiv.style.display = 'block';
}

function hideValidationResult() {
	document.getElementById('validation-result').style.display = 'none';
}

// Carregar estatísticas de hoje
function loadTodayStats() {
	fetch('/api/tickets/validation-stats/today', {
		headers: {
			'Accept': 'application/json',
		}
	})
	.then(response => response.json())
	.then(data => {
		document.getElementById('stats-validated').textContent = data.validated || 0;
		document.getElementById('stats-failed').textContent = data.failed || 0;
		
		const total = (data.validated || 0) + (data.failed || 0);
		const rate = total > 0 ? Math.round((data.validated / total) * 100) : 0;
		document.getElementById('stats-rate').textContent = rate + '%';
		
		// Atualizar histórico
		if (data.recent && data.recent.length > 0) {
			displayRecentValidations(data.recent);
		}
	})
	.catch(error => {
		console.error('Erro ao carregar stats:', error);
	});
}

// Adicionar ao histórico local
function addToRecentValidations(ticket, success) {
	const validation = {
		ticket_number: ticket.ticket_number,
		passenger_name: ticket.passenger_name || 'N/A',
		success: success,
		time: new Date(),
		error: ticket.error
	};
	
	recentValidations.unshift(validation);
	
	// Manter apenas últimas 10
	if (recentValidations.length > 10) {
		recentValidations = recentValidations.slice(0, 10);
	}
	
	displayRecentValidations(recentValidations);
}

// Exibir histórico
function displayRecentValidations(validations) {
	const container = document.getElementById('recent-validations');
	
	if (!validations || validations.length === 0) {
		container.innerHTML = `
			<div class="list-group-item text-center text-muted py-4">
				<i class="ph-clock-counter-clockwise ph-2x mb-2 d-block"></i>
				Nenhuma validação hoje
			</div>
		`;
		return;
	}
	
	let html = '';
	
	validations.forEach(v => {
		const iconClass = v.success ? 'ph-check-circle text-success' : 'ph-x-circle text-danger';
		const time = new Date(v.time || v.validated_at).toLocaleTimeString('pt-MZ');
		
		html += `
			<div class="list-group-item validation-item">
				<div class="d-flex justify-content-between align-items-start">
					<div class="flex-grow-1">
						<div class="d-flex align-items-center mb-1">
							<i class="${iconClass} me-2"></i>
							<span class="fw-semibold">${v.ticket_number}</span>
						</div>
						<small class="text-muted d-block">${v.passenger_name || 'N/A'}</small>
						${!v.success ? `<small class="text-danger">${v.error}</small>` : ''}
					</div>
					<span class="validation-time">${time}</span>
				</div>
			</div>
		`;
	});
	
	container.innerHTML = html;
}

// Sons de feedback
function playSuccessSound() {
	// Beep de sucesso
	const audioContext = new (window.AudioContext || window.webkitAudioContext)();
	const oscillator = audioContext.createOscillator();
	const gainNode = audioContext.createGain();
	
	oscillator.connect(gainNode);
	gainNode.connect(audioContext.destination);
	
	oscillator.frequency.value = 800;
	oscillator.type = 'sine';
	
	gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
	gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
	
	oscillator.start(audioContext.currentTime);
	oscillator.stop(audioContext.currentTime + 0.3);
}

function playErrorSound() {
	// Beep de erro
	const audioContext = new (window.AudioContext || window.webkitAudioContext)();
	const oscillator = audioContext.createOscillator();
	const gainNode = audioContext.createGain();
	
	oscillator.connect(gainNode);
	gainNode.connect(audioContext.destination);
	
	oscillator.frequency.value = 200;
	oscillator.type = 'sawtooth';
	
	gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
	gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
	
	oscillator.start(audioContext.currentTime);
	oscillator.stop(audioContext.currentTime + 0.5);
}

// Toast notifications
function showToast(message, type = 'info') {
	// Criar toast bootstrap ou usar alert simples
	const alert = document.createElement('div');
	alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
	alert.style.zIndex = '9999';
	alert.innerHTML = `
		${message}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	`;
	
	document.body.appendChild(alert);
	
	setTimeout(() => {
		alert.remove();
	}, 5000);
}

// Limpar ao sair da página
window.addEventListener('beforeunload', function() {
	if (isScanning) {
		stopScanning();
	}
});
</script>
@endpush
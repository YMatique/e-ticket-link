@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title')
	Home - <span class="fw-normal">Dashboard</span>
@endsection

@section('breadcrumbs')
	<a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
	<span class="breadcrumb-item active">Visão Geral</span>
@endsection

@section('header-actions')
	<div class="d-lg-flex mb-2 mb-lg-0">
		<div class="dropdown">
			<a href="#" class="d-flex align-items-center text-body dropdown-toggle py-2" data-bs-toggle="dropdown">
				<i class="ph-calendar me-2"></i>
				<span class="flex-1">Filtrar por Data</span>
			</a>

			<div class="dropdown-menu dropdown-menu-end">
				<a href="#" class="dropdown-item">Hoje</a>
				<a href="#" class="dropdown-item">Últimos 7 dias</a>
				<a href="#" class="dropdown-item">Últimos 30 dias</a>
				<a href="#" class="dropdown-item">Este mês</a>
			</div>
		</div>
	</div>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="row">
	<div class="col-sm-6 col-xl-3">
		<div class="card card-body">
			<div class="d-flex align-items-center">
				<div class="flex-fill">
					<h4 class="mb-0">{{ $todayTickets ?? 45 }}</h4>
					<span class="text-muted">Bilhetes Vendidos Hoje</span>
				</div>
				<div class="ms-3">
					<i class="ph-ticket ph-3x text-primary opacity-75"></i>
				</div>
			</div>
			<div class="w-75 mx-auto">
				<div class="progress h-1px mb-2" style="margin-top: 1rem;">
					<div class="progress-bar bg-warning" style="width: 100%"></div>
				</div>
				<div class="text-center text-muted fs-sm">
					<i class="ph-check-circle text-success me-1"></i>
					<span>100% disponíveis</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /statistics cards -->


<!-- Sales Chart -->
<div class="row">
	<div class="col-xl-8">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="mb-0">Vendas nos Últimos 7 Dias</h5>
				<div class="ms-auto">
					<a class="text-body" data-card-action="reload">
						<i class="ph-arrows-clockwise"></i>
					</a>
				</div>
			</div>

			<div class="card-body">
				<div class="chart-container">
					<canvas id="sales-chart" style="height: 300px;"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-4">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="mb-0">Métodos de Pagamento</h5>
			</div>

			<div class="card-body">
				<div class="chart-container">
					<canvas  id="payment-methods-chart" style="height: 300px;"></canvas>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table text-nowrap">
					<tbody>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-2 me-3">
										<i class="ph-device-mobile"></i>
									</div>
									<div>
										<div class="fw-semibold">M-Pesa</div>
										<span class="text-muted fs-sm">Pagamento móvel</span>
									</div>
								</div>
							</td>
							<td class="text-end">
								<div class="fw-semibold">65%</div>
								<span class="text-muted fs-sm">29 transacções</span>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
										<i class="ph-device-mobile"></i>
									</div>
									<div>
										<div class="fw-semibold">e-Mola</div>
										<span class="text-muted fs-sm">Pagamento móvel</span>
									</div>
								</div>
							</td>
							<td class="text-end">
								<div class="fw-semibold">25%</div>
								<span class="text-muted fs-sm">11 transacções</span>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="bg-warning bg-opacity-10 text-warning lh-1 rounded-pill p-2 me-3">
										<i class="ph-money"></i>
									</div>
									<div>
										<div class="fw-semibold">Dinheiro</div>
										<span class="text-muted fs-sm">Pagamento em dinheiro</span>
									</div>
								</div>
							</td>
							<td class="text-end">
								<div class="fw-semibold">10%</div>
								<span class="text-muted fs-sm">5 transacções</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- /sales chart -->


<!-- Upcoming Departures and Alerts -->
<div class="row">
	<div class="col-xl-6">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="mb-0">
					<i class="ph-rocket-launch me-2"></i>
					Próximas Partidas
				</h5>
				<a href="{{ route('schedules.index') }}" class="ms-auto">
					Ver todas
					<i class="ph-arrow-circle-right ms-1"></i>
				</a>
			</div>

			<div class="table-responsive">
				<table class="table text-nowrap">
					<thead>
						<tr>
							<th>Rota</th>
							<th>Horário</th>
							<th>Assentos</th>
							<th class="text-center">Status</th>
						</tr>
					</thead>
					<tbody>
						@forelse($upcomingSchedules ?? [] as $schedule)
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<i class="ph-map-pin text-primary me-2"></i>
									<div>
										<div class="fw-semibold">{{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}</div>
										<span class="text-muted fs-sm">{{ $schedule->bus->model }}</span>
									</div>
								</div>
							</td>
							<td>
								<div class="fw-semibold">{{ $schedule->departure_time }}</div>
								<span class="text-muted fs-sm">{{ $schedule->departure_date->format('d/m/Y') }}</span>
							</td>
							<td>
								<div class="fw-semibold">{{ $schedule->tickets_count ?? 0 }}/{{ $schedule->bus->total_seats }}</div>
								<div class="progress" style="height: 4px;">
									<div class="progress-bar bg-primary" style="width: {{ ($schedule->tickets_count ?? 0) / $schedule->bus->total_seats * 100 }}%"></div>
								</div>
							</td>
							<td class="text-center">
								<span class="badge bg-success bg-opacity-10 text-success">Activo</span>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="4" class="text-center text-muted py-4">
								<i class="ph-clock-countdown ph-2x mb-2 d-block"></i>
								Nenhuma partida programada
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			<div class="card-footer d-flex justify-content-between">
				<span class="text-muted">Total: {{ count($upcomingSchedules ?? []) }} viagens</span>
				<a href="{{ route('schedules.create') }}" class="fw-semibold">
					<i class="ph-plus-circle me-1"></i>
					Nova Viagem
				</a>
			</div>
		</div>
	</div>

	<div class="col-xl-6">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="mb-0">
					<i class="ph-bell-ringing me-2"></i>
					Alertas Recentes
				</h5>
			</div>

			<div class="card-body">
				<div class="d-flex align-items-start mb-3">
					<div class="me-3">
						<div class="bg-warning bg-opacity-10 text-warning lh-1 rounded-pill p-2">
							<i class="ph-warning"></i>
						</div>
					</div>
					<div class="flex-fill">
						<a href="#" class="fw-semibold">3 autocarros em manutenção</a>
						<div class="text-muted fs-sm">
							ABC-1234, XYZ-5678, DEF-9012 necessitam revisão técnica
						</div>
						<div class="text-muted fs-sm mt-1">
							<i class="ph-clock me-1"></i>
							Há 2 horas
						</div>
					</div>
				</div>

				<div class="d-flex align-items-start mb-3">
					<div class="me-3">
						<div class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2">
							<i class="ph-check-circle"></i>
						</div>
					</div>
					<div class="flex-fill">
						<a href="#" class="fw-semibold">12 pagamentos confirmados hoje</a>
						<div class="text-muted fs-sm">
							Total: 18,000 MT via M-Pesa e e-Mola
						</div>
						<div class="text-muted fs-sm mt-1">
							<i class="ph-clock me-1"></i>
							Há 3 horas
						</div>
					</div>
				</div>

				<div class="d-flex align-items-start mb-3">
					<div class="me-3">
						<div class="bg-info bg-opacity-10 text-info lh-1 rounded-pill p-2">
							<i class="ph-info"></i>
						</div>
					</div>
					<div class="flex-fill">
						<a href="#" class="fw-semibold">Rota Maputo-Beira com alta demanda</a>
						<div class="text-muted fs-sm">
							85% dos assentos já vendidos para esta semana
						</div>
						<div class="text-muted fs-sm mt-1">
							<i class="ph-clock me-1"></i>
							Há 5 horas
						</div>
					</div>
				</div>

				<div class="d-flex align-items-start">
					<div class="me-3">
						<div class="bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-2">
							<i class="ph-user-plus"></i>
						</div>
					</div>
					<div class="flex-fill">
						<a href="#" class="fw-semibold">28 novos passageiros registados</a>
						<div class="text-muted fs-sm">
							Crescimento de 15% em relação à semana passada
						</div>
						<div class="text-muted fs-sm mt-1">
							<i class="ph-clock me-1"></i>
							Há 1 dia
						</div>
					</div>
				</div>
			</div>

			<div class="card-footer">
				<a href="#" class="fw-semibold">
					Ver todos os alertas
					<i class="ph-arrow-circle-right ms-1"></i>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- /upcoming departures and alerts -->


<!-- Popular Routes -->
<div class="card">
	<div class="card-header d-flex align-items-center">
		<h5 class="mb-0">
			<i class="ph-trend-up me-2"></i>
			Rotas Mais Populares (Este Mês)
		</h5>
		<div class="ms-auto">
			<a href="{{ route('reports.routes') }}" class="fw-semibold">
				Ver relatório completo
				<i class="ph-arrow-circle-right ms-1"></i>
			</a>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table text-nowrap">
			<thead>
				<tr>
					<th>#</th>
					<th>Rota</th>
					<th>Bilhetes Vendidos</th>
					<th>Receita Total</th>
					<th>Ocupação Média</th>
					<th class="text-center">Tendência</th>
				</tr>
			</thead>
			<tbody>
				@forelse($popularRoutes ?? [] as $index => $route)
				<tr>
					<td>
						<span class="badge {{ $index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-light text-dark') }}">
							{{ $index + 1 }}
						</span>
					</td>
					<td>
						<div class="d-flex align-items-center">
							<i class="ph-map-trifold text-primary me-2"></i>
							<div>
								<div class="fw-semibold">{{ $route->originCity->name }} → {{ $route->destinationCity->name }}</div>
								<span class="text-muted fs-sm">{{ $route->originCity->province->name }} - {{ $route->destinationCity->province->name }}</span>
							</div>
						</div>
					</td>
					<td>
						<div class="fw-semibold">{{ $route->tickets_count ?? 0 }}</div>
						<span class="text-muted fs-sm">bilhetes</span>
					</td>
					<td>
						<div class="fw-semibold">{{ number_format($route->revenue ?? 0, 2) }} MT</div>
					</td>
					<td>
						<div class="d-flex align-items-center">
							<div class="progress flex-fill" style="height: 6px;">
								<div class="progress-bar bg-primary" style="width: {{ $route->occupancy ?? 0 }}%"></div>
							</div>
							<span class="ms-2 fw-semibold">{{ $route->occupancy ?? 0 }}%</span>
						</div>
					</td>
					<td class="text-center">
						<i class="ph-trend-up text-success ph-lg"></i>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="6" class="text-center text-muted py-4">
						<i class="ph-chart-line-down ph-2x mb-2 d-block"></i>
						Nenhum dado disponível
					</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
<!-- /popular routes -->


<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Testar Toast Notifications</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <button onclick="toast.success('Operação bem-sucedida!')" class="btn btn-success w-100">
                    <i class="ph-check-circle me-2"></i>
                    Toast Sucesso
                </button>
            </div>
            <div class="col-md-3">
                <button onclick="toast.error('Algo deu errado!')" class="btn btn-danger w-100">
                    <i class="ph-x-circle me-2"></i>
                    Toast Erro
                </button>
            </div>
            <div class="col-md-3">
                <button onclick="toast.warning('Atenção necessária!')" class="btn btn-warning w-100">
                    <i class="ph-warning-circle me-2"></i>
                    Toast Aviso
                </button>
            </div>
            <div class="col-md-3">
                <button onclick="toast.info('Informação útil!')" class="btn btn-info w-100">
                    <i class="ph-info me-2"></i>
                    Toast Info
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="{{ asset('template/assets/demo/charts/pages/dashboard/lines.js') }}"></script>
<script src="{{ asset('template/assets/demo/charts/pages/dashboard/donuts.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const salesChart = function() {
    const element = document.getElementById('sales-chart');
    if (!element) return;

    const ctx = element.getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
            datasets: [{
                label: 'Bilhetes Vendidos',
                data: [25, 32, 28, 45, 38, 52, 45],
                borderColor: '#E63946',
                backgroundColor: 'rgba(230, 57, 70, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#E63946',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1D3557',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' bilhetes';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e0e0e0',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#666'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#666'
                    }
                }
            }
        }
    });
};


// Gráfico de Métodos de Pagamento (Donut)
const paymentMethodsChart = function() {
    const element = document.getElementById('payment-methods-chart');
    if (!element) return;

    const ctx = element.getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['M-Pesa', 'e-Mola', 'Dinheiro'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: [
                    '#E63946',
                    '#06D6A0',
                    '#FFC107'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1D3557',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    salesChart();
    paymentMethodsChart();
});
</script>
@endpush
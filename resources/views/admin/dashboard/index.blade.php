@extends('layouts.app')

@section('title', 'Dashboard - Visão Geral')

@section('content')
<div class="content">

    <!-- Page Header com Filtro de Período -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex align-items-center">
            <div class="page-title">
                <h4>
                    <i class="ph-house-line me-2"></i>
                    <span class="fw-semibold">Dashboard</span>
                    <small class="d-block text-muted mt-1">Visão geral do sistema em tempo real</small>
                </h4>
            </div>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="fs-sm text-muted">Período selecionado</div>
                    <div class="fw-bold">
                        {{ $period === 'today' ? 'Hoje' : 
                           ($period === '7days' ? 'Últimos 7 dias' : 
                           ($period === '30days' ? 'Últimos 30 dias' : 'Este mês')) }}
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="ph-calendar me-2"></i>
                        Filtrar Período
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="{{ route('dashboard', ['period' => 'today']) }}" 
                           class="dropdown-item {{ $period === 'today' ? 'active bg-primary text-white' : '' }}">Hoje</a>
                        <a href="{{ route('dashboard', ['period' => '7days']) }}" 
                           class="dropdown-item {{ $period === '7days' ? 'active bg-primary text-white' : '' }}">Últimos 7 dias</a>
                        <a href="{{ route('dashboard', ['period' => '30days']) }}" 
                           class="dropdown-item {{ $period === '30days' ? 'active bg-primary text-white' : '' }}">Últimos 30 dias</a>
                        <a href="{{ route('dashboard', ['period' => 'month']) }}" 
                           class="dropdown-item {{ $period === 'month' ? 'active bg-primary text-white' : '' }}">Este mês</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Principais -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-start border-start-4 border-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h3 class="mb-1 text-primary">{{ $todayTickets }}</h3>
                            <p class="mb-0 text-muted">Bilhetes Vendidos Hoje</p>
                        </div>
                        <div class="ms-3">
                            <i class="ph-ticket ph-3x text-primary opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-start border-start-4 border-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h3 class="mb-1 text-success">{{ number_format($periodRevenue, 0, ',', '.') }} MT</h3>
                            <p class="mb-0 text-muted">Receita do Período</p>
                        </div>
                        <div class="ms-3">
                            <i class="ph-money ph-3x text-success opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-start border-start-4 border-info shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h3 class="mb-1 text-info">{{ $periodTickets }}</h3>
                            <p class="mb-0 text-muted">Total Bilhetes Vendidos</p>
                        </div>
                        <div class="ms-3">
                            <i class="ph-receipt ph-3x text-info opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-start border-start-4 border-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h3 class="mb-1 text-warning">{{ $newPassengers }}</h3>
                            <p class="mb-0 text-muted">Novos Passageiros</p>
                        </div>
                        <div class="ms-3">
                            <i class="ph-user-plus ph-3x text-warning opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico + Próximas Partidas -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="ph-chart-line-up me-2"></i> Vendas nos Últimos 7 Dias</h5>
                    <small class="text-muted">Atualizado agora</small>
                </div>
                <div class="card-body">
<div class="chart-container">
                <canvas id="salesChart" class="chart-canvas"></canvas>
            </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="ph-rocket-launch me-2"></i> Próximas Partidas (24h)</h5>
                    <a href="{{ route('schedules.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($upcomingSchedules as $item)
                        @php $s = $item->schedule; @endphp
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-fill">
                                    <div class="fw-bold">{{ $s->route->originCity->name }} → {{ $s->route->destinationCity->name }}</div>
                                    <div class="text-muted small">
                                        <i class="ph-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($s->departure_date . ' ' . $s->departure_time)->format('H:i') }}
                                        • {{ $s->bus->displayName() }}
                                    </div>
                                </div>
                                <div class="text-end ms-3">
                                    <div class="fw-bold text-primary">{{ $item->tickets_count }}/{{ $s->bus->total_seats }}</div>
                                    <div class="progress mt-1" style="height:6px;width:60px">
                                        <div class="progress-bar {{ $item->occupancy >= 80 ? 'bg-danger' : 'bg-success' }}"
                                             style="width: {{ $item->occupancy }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="ph-calendar-x ph-3x mb-3 d-block"></i>
                            Nenhuma partida nas próximas 24h
                        </div>
                    @endforelse
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                        <i class="ph-plus-circle me-2"></i> Nova Viagem
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rotas Populares + Alertas -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="ph-trend-up me-2"></i> Rotas Mais Populares (Este Mês)</h5>
                    <a href="{{ route('reports.routes') }}" class="btn btn-sm btn-outline-secondary">
                        Relatório completo
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Rota</th>
                                <th class="text-center">Viagens</th>
                                <th class="text-center">Bilhetes</th>
                                <th class="text-end">Receita</th>
                                <th class="text-center">Ocupação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularRoutes as $index => $item)
                                @php $r = $item->route; @endphp
                                <tr>
                                    <td>
                                        <span class="badge rounded-pill {{ $index == 0 ? 'bg-warning text-dark' : 'bg-light text-dark' }}">
                                            {{ $loop->iteration }}º
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $r->originCity->name }} → {{ $r->destinationCity->name }}</div>
                                        <small class="text-muted">{{ $r->distance_km }} km • {{ $r->durationInHours() }}h</small>
                                    </td>
                                    <td class="text-center"><strong>{{ $r->trips_this_month ?? 0 }}</strong></td>
                                    <td class="text-center"><strong class="text-primary">{{ $item->tickets_count }}</strong></td>
                                    <td class="text-end fw-bold text-success">{{ number_format($item->revenue, 0, ',', '.') }} MT</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 28px;">
                                            <div class="progress-bar {{ $item->occupancy >= 90 ? 'bg-danger' : ($item->occupancy >= 70 ? 'bg-warning' : 'bg-success') }}"
                                                 style="width: {{ $item->occupancy }}%">
                                                {{ $item->occupancy }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="ph-signpost ph-3x mb-3"></i>
                                        Nenhuma venda este mês
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm bg-gradient-primary text-white">
                <div class="card-body text-center py-5">
                    <i class="ph-smiley ph-5x mb-3 opacity-75"></i>
                    <h3 class="mb-2">Parabéns!</h3>
                    <p class="mb-3 opacity-90">
                        Já vendeste <strong>{{ number_format($periodTickets) }}</strong> bilhetes este período<br>
                        e geraste <strong>{{ number_format($periodRevenue, 0, ',', '.') }} MT</strong> em receita!
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('tickets.create') }}" class="btn btn-light">
                            <i class="ph-plus me-2"></i> Novo Bilhete
                        </a>
                        <a href="{{ route('reports.sales') }}" class="btn btn-outline-light">
                            Ver Relatórios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 380px;
        width: 100%;
    }
    
    .chart-canvas {
        height: 100% !important;
        width: 100% !important;
    }
    
    @media (max-width: 1200px) {
        .chart-container { height: 340px; }
    }
    
    @media (max-width: 992px) {
        .chart-container { height: 300px; }
    }
    
    @media (max-width: 576px) {
        .chart-container { height: 260px; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesData->pluck('date')),
            datasets: [{
                label: 'Bilhetes Vendidos',
                data: @json($salesData->pluck('tickets')),
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointBackgroundColor: '#4361ee',
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    callbacks: {
                        label: ctx => ctx.parsed.y + ' bilhetes vendidos'
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
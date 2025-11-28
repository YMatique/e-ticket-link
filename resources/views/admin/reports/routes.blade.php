@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <h4>
                <i class="ph-map-trifold me-2"></i>
                <span class="fw-semibold">Relatório por Rotas</span>
            </h4>
            <div class="ms-auto">
                <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="routes">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" class="btn btn-danger">
                        <i class="ph-file-pdf me-2"></i> PDF
                    </button>
                </form>
                <form method="POST" action="{{ route('reports.export.excel') }}" class="d-inline ms-2">
                    @csrf
                    <input type="hidden" name="type" value="routes">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" class="btn btn-success">
                        <i class="ph-file-xlsx me-2"></i> Excel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Filtros de Data -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Data Inicial</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Data Final</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ph-funnel me-2"></i> Filtrar
                    </button>
                    <a href="{{ route('reports.routes') }}" class="btn btn-light ms-2">
                        Últimos 30 dias
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success bg-opacity-10 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-success">{{ number_format($routes->sum('tickets_sum_price'), 2) }} MT</h4>
                        <span>Receita Total</span>
                    </div>
                    <i class="ph-money ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary bg-opacity-10 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-primary">{{ $routes->sum('tickets_count') }}</h4>
                        <span>Bilhetes Vendidos</span>
                    </div>
                    <i class="ph-ticket ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-info bg-opacity-10 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-info">{{ $routes->where('tickets_count', '>', 0)->count() }}</h4>
                        <span>Rotas Ativas</span>
                    </div>
                    <i class="ph-signpost ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-warning bg-opacity-10 border-warning">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-warning">
                            @if($routes->where('tickets_count', '>', 0)->count() > 0)
                                {{ $routes->sortByDesc('tickets_count')->first()->originCity->name }} → {{ $routes->sortByDesc('tickets_count')->first()->destinationCity->name }}
                            @else
                                —
                            @endif
                        </h4>
                        <span>Rota Mais Popular</span>
                    </div>
                    <i class="ph-medal ph-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de Pizza -->
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Distribuição de Vendas por Rota</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="routesPieChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabela Detalhada -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Ranking de Rotas</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Rota</th>
                                <th class="text-center">Bilhetes</th>
                                <th class="text-end">Receita</th>
                                <th class="text-center">Ocupação Média</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routes->where('tickets_count', '>', 0) as $index => $route)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'secondary')) }} text-dark">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $route->originCity->name }} <i class="ph-arrow-right mx-1 text-muted"></i> {{ $route->destinationCity->name }}
                                        </div>
                                        <small class="text-muted">{{ $route->distance_km }} km • {{ $route->durationInHours() }}h</small>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $route->tickets_count }}</strong>
                                    </td>
                                    <td class="text-end fw-semibold text-success">
                                        {{ number_format($route->tickets_sum_price, 2) }} MT
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $occupancy = $route->tickets_count > 0 
                                                ? round(($route->tickets_count / $route->schedules->sum('bus.total_seats')) * 100, 1)
                                                : 0;
                                        @endphp
                                        <div class="progress" style="height: 24px;">
                                            <div class="progress-bar {{ $occupancy >= 80 ? 'bg-success' : ($occupancy >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                                 style="width: {{ $occupancy }}%">
                                                {{ $occupancy }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="ph-signpost ph-3x mb-3 d-block"></i>
                                        Nenhuma rota com vendas no período selecionado
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
    .bg-gold { background: linear-gradient(45deg, #FFD700, #FFA500) !important; }
    .bg-silver { background: linear-gradient(45deg, #C0C0C0, #A9A9A9) !important; }
    .bg-bronze { background: linear-gradient(45deg, #CD7F32, #B87333) !important; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('routesPieChart').getContext('2d');

    const data = {
        labels: @json($routes->where('tickets_count', '>', 0)->map(fn($r) => $r->originCity->name . ' → ' . $r->destinationCity->name)),
        datasets: [{
            label: 'Receita por Rota',
            data: @json($routes->where('tickets_count', '>', 0)->pluck('tickets_sum_price')),
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#C9CBCF', '#E7E9ED', '#76D7C4', '#F1948A'
            ],
            hoverOffset: 20
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' },
                tooltip: {
                    callbacks: {
                        label: context => {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value.toLocaleString()} MT (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
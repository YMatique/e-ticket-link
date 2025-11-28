@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex pt-2">
            <h4>
                <i class="ph-chart-line-up me-2"></i>
                <span class="fw-semibold">Relatório de Vendas</span>
            </h4>
            <div class="ms-auto">
                <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="sales">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" class="btn btn-danger">
                        <i class="ph-file-pdf me-2"></i> PDF
                    </button>
                </form>
                <form method="POST" action="{{ route('reports.export.excel') }}" class="d-inline ms-2">
                    @csrf
                    <input type="hidden" name="type" value="sales">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" class="btn btn-success">
                        <i class="ph-file-xlsx me-2"></i> Excel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Filtros -->
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
                        <i class="ph-funnel me-2"></i> Atualizar
                    </button>
                    <a href="{{ route('reports.sales') }}" class="btn btn-light ms-2">Hoje (30 dias)</a>
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
                        <h4 class="mb-0 text-success">{{ number_format($dailySales->sum('total'), 2) }} MT</h4>
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
                        <h4 class="mb-0 text-primary">{{ $dailySales->sum('tickets') }}</h4>
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
                        <h4 class="mb-0 text-info">{{ number_format($dailySales->avg('total') ?? 0, 2) }} MT</h4>
                        <span>Média Diária</span>
                    </div>
                    <i class="ph-trend-up ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-warning bg-opacity-10 border-warning">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-warning">{{ number_format($dailySales->max('total') ?? 0, 2) }} MT</h4>
                        <span>Melhor Dia</span>
                    </div>
                    <i class="ph-trophy ph-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Linhas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Evolução Diária de Vendas</h5>
        </div>
        <div class="card-body">
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>

    <!-- Tabela por Rota -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Vendas por Rota</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Rota</th>
                        <th class="text-center">Bilhetes</th>
                        <th class="text-end">Receita</th>
                        <th class="text-end">% do Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routeSales as $sale)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <strong>{{ $sale->schedule->route->originCity->name }}</strong>
                                    <i class="ph-arrow-right text-muted"></i>
                                    <strong>{{ $sale->schedule->route->destinationCity->name }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $sale->tickets }}</span>
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($sale->revenue, 2) }} MT</td>
                            <td class="text-end">
                                @php
                                    $percent = $dailySales->sum('total') > 0 
                                        ? ($sale->revenue / $dailySales->sum('total')) * 100 
                                        : 0;
                                @endphp
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percent }}%">
                                        {{ number_format($percent, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="ph-receipt ph-3x mb-3 d-block"></i>
                                Nenhuma venda neste período
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    const data = {
        labels: @json($dailySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))),
        datasets: [{
            label: 'Receita Diária (MT)',
            data: @json($dailySales->pluck('total')),
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#28a745',
            pointRadius: 5,
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => value.toLocaleString() + ' MT' }
                }
            }
        }
    });
});
</script>
@endpush
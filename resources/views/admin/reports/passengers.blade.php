@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <h4>
                <i class="ph-users-four me-2"></i>
                <span class="fw-semibold">Top Passageiros</span>
            </h4>
            <div class="ms-auto">
                <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="passengers">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button class="btn btn-danger"><i class="ph-file-pdf me-2"></i> PDF</button>
                </form>
                <form method="POST" action="{{ route('reports.export.excel') }}" class="d-inline ms-2">
                    @csrf
                    <input type="hidden" name="type" value="passengers">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button class="btn btn-success"><i class="ph-file-xlsx me-2"></i> Excel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Período Inicial</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Período Final</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary"><i class="ph-funnel me-2"></i> Filtrar</button>
                    <a href="{{ route('reports.passengers') }}" class="btn btn-light ms-2">Últimos 90 dias</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumo Rápido -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary bg-opacity-10 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-primary">{{ $passengers->total() }}</h4>
                        <span>Passageiros Ativos</span>
                    </div>
                    <i class="ph-users ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success bg-opacity-10 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-success">{{ number_format($passengers->sum('total_spent'), 2) }} MT</h4>
                        <span>Total Gasto</span>
                    </div>
                    <i class="ph-money ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-info bg-opacity-10 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-info">{{ $passengers->avg('tickets_paid_count') ? round($passengers->avg('tickets_paid_count'), 1) : 0 }}</h4>
                        <span>Média de Viagens</span>
                    </div>
                    <i class="ph-path ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-warning bg-opacity-10 border-warning">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-warning">
                            @if($passengers->first())
                                {{ $passengers->first()->fullName() }}
                            @else —
                            @endif
                        </h4>
                        <span>Melhor Cliente</span>
                    </div>
                    <i class="ph-crown ph-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico Horizontal -->
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Top 10 Clientes (Valor Gasto)</h5>
                </div>
                <div class="card-body">
                    <canvas id="topPassengersChart" height="400"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabela Ranking -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ranking Completo de Passageiros</h5>
                    <small class="text-muted">{{ $passengers->total() }} passageiros</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Passageiro</th>
                                <th class="text-center">Viagens</th>
                                <th class="text-end">Total Gasto</th>
                                <th class="text-center">Média por Viagem</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($passengers as $passenger)
                                <tr>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $loop->index < 3 ? ['gold','silver','bronze'][$loop->index] : 'secondary' }} text-dark fw-bold">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                                                 style="width:45px;height:45px;">
                                                {{ $passenger->initials() }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $passenger->fullName() }}</div>
                                                <small class="text-muted">{{ $passenger->phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-primary">{{ $passenger->tickets_paid_count }}</strong>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ number_format($passenger->total_spent, 2) }} MT
                                    </td>
                                    <td class="text-center">
                                        @php $avg = $passenger->tickets_paid_count > 0 ? round($passenger->total_spent / $passenger->tickets_paid_count, 2) : 0; @endphp
                                        <span class="badge bg-info">{{ $avg }} MT</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('passengers.show', $passenger) }}" class="btn btn-sm btn-light">
                                            <i class="ph-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="ph-users ph-3x mb-3 d-block"></i>
                                        Nenhum passageiro com compras no período
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $passengers->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('topPassengersChart').getContext('2d');

    const top10 = @json($passengers->take(10));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: top10.map(p => p.full_name.substring(0, 20) + (p.full_name.length > 20 ? '...' : '')),
            datasets: [{
                label: 'Total Gasto (MT)',
                data: top10.map(p => p.total_spent),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: '#0d6efd',
                borderWidth: 1,
                borderRadius: 8,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.parsed.x.toLocaleString() + ' MT'
                    }
                }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
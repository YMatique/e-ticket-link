@extends('layouts.app')

@section('content')
    <div class="content">

        <!-- Header -->
        <div class="page-header page-header-light shadow mb-4">
            <div class="page-header-content d-flex">
                <h4>
                    <i class="ph-bus me-2"></i>
                    <span class="fw-semibold">Relatório de Autocarros</span>
                </h4>
                <div class="ms-auto">
                    <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="buses">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button class="btn btn-danger"><i class="ph-file-pdf me-2"></i> PDF</button>
                    </form>
                    <form method="POST" action="{{ route('reports.export.excel') }}" class="d-inline ms-2">
                        @csrf
                        <input type="hidden" name="type" value="buses">
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
                    <div class="col-md-5">
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-5">
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cards Resumo -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-primary bg-opacity-10 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h4 class="mb-0 text-primary">{{ $buses->count() }}</h4>
                            <span>Total de Autocarros</span>
                        </div>
                        <i class="ph-bus ph-2x text-primary opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success bg-opacity-10 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h4 class="mb-0 text-success">{{ number_format($buses->sum('total_revenue'), 2) }} MT</h4>
                            <span>Receita Total</span>
                        </div>
                        <i class="ph-money ph-2x text-success opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-info bg-opacity-10 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h4 class="mb-0 text-info">{{ $buses->sum('total_trips') }}</h4>
                            <span>Viagens Realizadas</span>
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
                                @if ($buses->first())
                                    {{ $buses->first()->displayName() }}
                                @else
                                    —
                                @endif
                            </h4>
                            <span>Mais Rentável</span>
                        </div>
                        <i class="ph-trophy ph-2x text-warning opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico + Tabela -->
        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Receita por Autocarro</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="busesRevenueChart" height="380"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Detalhes por Autocarro</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Autocarro</th>
                                    <th class="text-center">Viagens</th>
                                    <th class="text-center">Bilhetes</th>
                                    <th class="text-end">Receita</th>
                                    <th class="text-center">Ocupação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($buses as $bus)
                                    @php
                                        $occupancy =
                                            $bus->total_trips > 0
                                                ? round(
                                                    ($bus->tickets_sold_count /
                                                        ($bus->total_trips * $bus->total_seats)) *
                                                        100,
                                                    1,
                                                )
                                                : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span
                                                class="badge bg-{{ $loop->index < 3 ? ['gold', 'silver', 'bronze'][$loop->index] : 'secondary' }} text-dark">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $bus->displayName() }}</div>
                                            <small class="text-muted">{{ $bus->total_seats }} lugares</small>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $bus->total_trips }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $bus->total_tickets_sold_count }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ number_format($bus->total_revenue, 2) }} MT
                                        </td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 28px;">
                                                <div class="progress-bar {{ $occupancy >= 80 ? 'bg-success' : ($occupancy >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                                    style="width: {{ $occupancy }}%">
                                                    {{ $occupancy }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="ph-bus ph-3x mb-3 d-block"></i>
                                            Nenhum autocarro ativo no período
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

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('busesRevenueChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($buses->pluck('registration_number')),
                    datasets: [{
                        label: 'Receita (MT)',
                        data: @json($buses->pluck('total_revenue')),
                        backgroundColor: 'rgba(255, 159, 64, 0.8)',
                        borderColor: '#ff9f40',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.parsed.y.toLocaleString() + ' MT'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection

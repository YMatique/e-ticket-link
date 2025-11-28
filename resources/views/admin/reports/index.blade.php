@extends('layouts.app')

@section('content')
<div class="content">
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <h4><i class="ph-chart-pie me-2"></i> Relatórios e Estatísticas</h4>
        </div>
    </div>

    <!-- Filtros de Data -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary">Atualizar</button>
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
                        <h4 class="mb-0 text-success">{{ number_format($stats['total_revenue'], 2) }} MT</h4>
                        <span>Receita Total</span>
                    </div>
                    <i class="ph-trend-up ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <!-- Outros cards aqui... -->
    </div>

    <!-- Navegação rápida -->
    <div class="card">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3"><a href="{{ route('reports.sales') }}" class="btn btn-outline-primary w-100">Vendas</a></div>
                <div class="col-md-3"><a href="{{ route('reports.routes') }}" class="btn btn-outline-info w-100">Rotas</a></div>
                <div class="col-md-3"><a href="{{ route('reports.passengers') }}" class="btn btn-outline-warning w-100">Passageiros</a></div>
                <div class="col-md-3"><a href="{{ route('reports.buses') }}" class="btn btn-outline-danger w-100">Autocarros</a></div>
            </div>
        </div>
    </div>
</div>
@endsection
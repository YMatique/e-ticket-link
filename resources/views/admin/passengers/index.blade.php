{{-- resources/views/admin/passengers/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <div class="page-title">
                <h4>
                    <i class="ph-users me-2"></i>
                    <span class="fw-semibold">Passageiros</span>
                </h4>
            </div>
            <div class="my-auto ms-auto">
                <a href="{{ route('passengers.create') }}" class="btn btn-primary">
                    <i class="ph-plus me-2"></i>
                    Novo Passageiro
                </a>
            </div>
        </div>
    </div>

    <!-- STATISTICS CARDS (igual à página de Bilhetes) -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
                        <span class="text-muted">Total de Passageiros</span>
                    </div>
                    <i class="ph-users ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ number_format($stats['active']) }}</h4>
                        <span class="text-muted">Ativos</span>
                    </div>
                    <i class="ph-user-check ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ number_format($stats['with_tickets']) }}</h4>
                        <span class="text-muted">Com Bilhetes</span>
                    </div>
                    <i class="ph-ticket ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ number_format($stats['today']) }}</h4>
                        <span class="text-muted">Registados Hoje</span>
                    </div>
                    <i class="ph-calendar-plus ph-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="ph-funnel me-2"></i> Filtros</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nome, email, telefone, BI..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ph-magnifying-glass me-1"></i> Buscar
                    </button>
                    <a href="{{ route('passengers.index') }}" class="btn btn-light">
                        <i class="ph-x me-1"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Passageiros -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Lista de Passageiros</h5>
            <div class="ms-auto">
                <span class="badge bg-primary">{{ $passengers->total() }} passageiros</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome Completo</th>
                        <th>Contacto</th>
                        <th>Documento</th>
                        <th>Bilhetes</th>
                        <th>Cadastro</th>
                        <th>Estado</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passengers as $p)
                        <tr>
                            <td>
                                <a href="{{ route('passengers.show', $p) }}" class="fw-semibold text-primary">
                                    {{ $p->first_name }} {{ $p->last_name }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $p->email }}</small>
                            </td>
                            <td>
                                <div class="text-success">{{ $p->phone }}</div>
                            </td>
                            <td>
                                <span class="text-muted">{{ strtoupper($p->document_type) }}</span><br>
                                <strong>{{ $p->document_number }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-20 text-info">
                                    <i class="ph-ticket me-1"></i>
                                    {{ $p->tickets_count }}
                                </span>
                            </td>
                            <td>
                                {{ $p->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $p->is_active ? 'success' : 'danger' }} bg-opacity-10 text-{{ $p->is_active ? 'success' : 'danger' }}">
                                    {{ $p->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('passengers.show', $p) }}" class="btn btn-sm btn-light" title="Ver">
                                        <i class="ph-eye"></i>
                                    </a>
                                    <a href="{{ route('passengers.edit', $p) }}" class="btn btn-sm btn-light" title="Editar">
                                        <i class="ph-pencil-simple"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light" title="{{ $p->is_active ? 'Desativar' : 'Ativar' }}"
                                            onclick="event.preventDefault(); document.getElementById('toggle-{{ $p->id }}').submit();">
                                        <i class="ph-user-switch"></i>
                                    </button>
                                    <form id="toggle-{{ $p->id }}" action="{{ route('passengers.toggle', $p) }}" method="POST" class="d-inline">
                                        @csrf
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="ph-users ph-3x mb-3 d-block"></i>
                                Nenhum passageiro encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($passengers->hasPages())
            <div class="card-footer">
                {{ $passengers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
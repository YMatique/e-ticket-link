@extends('layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-header-content d-flex">
            <h4><i class="ph-users me-2"></i> Passageiros</h4>
            <a href="{{ route('passengers.create') }}" class="btn btn-primary ms-auto">
                <i class="ph-plus"></i> Novo Passageiro
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Nome, email, BI..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary">Buscar</button>
                    <a href="{{ route('passengers.index') }}" class="btn btn-light">Limpar</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Contacto</th>
                        <th>Documento</th>
                        <th>Bilhetes</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($passengers as $p)
                    <tr>
                        <td>
                            <a href="{{ route('passengers.show', $p) }}" class="fw-semibold">
                                {{ $p->first_name }} {{ $p->last_name }}
                            </a>
                        </td>
                        <td>
                            <div>{{ $p->phone }}</div>
                            <small class="text-muted">{{ $p->email }}</small>
                        </td>
                        <td>{{ strtoupper($p->document_type) }}: {{ $p->document_number }}</td>
                        <td><span class="badge bg-info">{{ $p->tickets_count }}</span></td>
                        <td>
                            <span class="badge bg-{{ $p->is_active ? 'success' : 'danger' }} bg-opacity-10 text-{{ $p->is_active ? 'success' : 'danger' }}">
                                {{ $p->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('passengers.edit', $p) }}" class="btn btn-sm btn-light">Editar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $passengers->links() }}
        </div>
    </div>
</div>
@endsection
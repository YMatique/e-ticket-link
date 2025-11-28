@extends('layouts.app')

@section('content')
<div class="content">

    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex pt-2 pb-2">
            <h4><i class="ph-users-three me-2"></i> Utilizadores do Sistema</h4>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary ms-auto">
                <i class="ph-plus me-2"></i> Novo Utilizador
            </a>
        </div>
    </div>

    <!-- Cards de estatísticas -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $stats['total'] }}</h4>
                        <span class="text-muted">Total</span>
                    </div>
                    <i class="ph-users ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success bg-opacity-10 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-success">{{ $stats['active'] }}</h4>
                        <span>Ativos</span>
                    </div>
                    <i class="ph-user-check ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-info bg-opacity-10 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-info">{{ $stats['admin'] }}</h4>
                        <span>Administradores</span>
                    </div>
                    <i class="ph-crown ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-warning bg-opacity-10 border-warning">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-warning">{{ $stats['today'] }}</h4>
                        <span>Criados Hoje</span>
                    </div>
                    <i class="ph-calendar-plus ph-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Nome ou email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary">Buscar</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light">Limpar</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Utilizador</th>
                        <th>Função</th>
                        <th>Estado</th>
                        <th>Criado em</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                                     style="width:40px;height:40px;">
                                    {{ $user->initials() }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ $role->name === 'Admin' ? 'danger' : ($role->name === 'Agente' ? 'primary' : 'info') }} bg-opacity-10 text-{{ $role->name === 'Admin' ? 'danger' : ($role->name === 'Agente' ? 'primary' : 'info') }}">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status =='active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $user->status=='active' ? 'success' : 'secondary' }}">
                                 @php
                                       $array = ['active'=>'Ativo','inactive'=>'Desativado', 'blocked'=>'Bloqueado'];
                                       $userStatus = $array[$user->status];
                                   @endphp
                                    {{  $userStatus  }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light">
                                <i class="ph-pencil-simple"></i>
                            </a>
                            <form action="{{ route('users.toggle', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light" title="{{ $user->status=='active' ? 'Desativar' : 'Ativar' }}">
                                    <i class="ph-user-switch"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
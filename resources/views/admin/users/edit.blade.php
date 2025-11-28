@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <div class="page-title">
                <h4>
                    <i class="ph-pencil-simple me-2"></i>
                    <span class="fw-semibold">Editar Utilizador</span>
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-light mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilizadores</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </nav>
            </div>
            <div class="my-auto ms-auto">
                <a href="{{ route('users.index') }}" class="btn btn-light">
                    <i class="ph-arrow-left me-2"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                                 style="width:60px;height:60px;font-size:28px;">
                                {{ $user->initials() }}
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $user->name }}</h5>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>

                        <!-- Estado rápido -->
                        <div class="ms-auto">
                            <form action="{{ route('users.toggle', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'danger' : 'success' }} btn-label">
                                    <i class="ph-user-switch me-1"></i>
                                    {{ $user->status=='active' ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- Nome e Email -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password (opcional) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nova Palavra-passe</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Deixe em branco para manter atual">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar Nova Palavra-passe</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mb-4">
                            <label class="form-label d-block">Funções (Roles) <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach($roles as $role)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]"
                                                   value="{{ $role->name }}" id="role-{{ $role->id }}"
                                                   {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="role-{{ $role->id }}">
                                                @if($role->name === 'Admin')
                                                    <span class="text-danger"><i class="ph-crown me-2"></i> Administrador</span>
                                                @elseif($role->name === 'Agente')
                                                    <span class="text-primary"><i class="ph-user-gear me-2"></i> Agente</span>
                                                @elseif($role->name === 'Fiscal')
                                                    <span class="text-info"><i class="ph-qr-code me-2"></i> Fiscal</span>
                                                @else
                                                    {{ $role->name }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="ph-warning me-2"></i>
                            <strong>Atenção:</strong> Alterar as funções de um utilizador afeta imediatamente o seu acesso ao sistema.
                        </div>

                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('users.index') }}" class="btn btn-light">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="ph-check me-2"></i>
                            Guardar Alterações
                        </button>
                    </div>
                </div>
            </form>

            <!-- Apagar utilizador (só se não for diferente do atual) -->
            @if(auth()->id() !== $user->id)
                <div class="card border-danger mt-3">
                    <div class="card-body text-center">
                        <p class="mb-3 text-danger"><strong>Zona de perigo</strong></p>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Tem a certeza que quer eliminar este utilizador? Esta ação não pode ser irreversível.')">
                                <i class="ph-trash me-2"></i>
                                Eliminar Utilizador
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph-clock-counter-clockwise me-2"></i> Informações da Conta</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Criado em</td>
                            <td class="text-end">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Último login</td>
                            <td class="text-end">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado</td>
                            <td class="text-end">
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $user->status=='active' ? 'success' : 'danger' }}">
                                   @php
                                       $array = ['active'=>'Ativo','inactive'=>'Desativado', 'blocked'=>'Bloqueado'];
                                       $userStatus = $array[$user->status];
                                   @endphp
                                    {{  $userStatus  }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3 bg-light">
                <div class="card-body">
                    <h6 class="mb-3"><i class="ph-shield-check me-2"></i> Permissões Atuais</h6>
                    @if($user->roles->isEmpty())
                        <p class="text-muted small">Nenhuma função atribuída</p>
                    @else
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-1 d-inline-block">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
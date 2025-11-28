@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <div class="page-title">
                <h4>
                    <i class="ph-user-plus me-2"></i>
                    <span class="fw-semibold">Novo Utilizador do Sistema</span>
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-light mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilizadores</a></li>
                        <li class="breadcrumb-item active">Novo</li>
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
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-user-circle me-2"></i>
                            Dados do Utilizador
                        </h5>
                    </div>

                    <div class="card-body">

                        <!-- Nome e Email -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Palavra-passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar Palavra-passe <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mb-4">
                            <label class="form-label d-block">Funções (Roles) <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach($roles as $role)
                                    <div class="col-md-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="roles[]"
                                                   value="{{ $role->name }}" id="role-{{ $role->id }}"
                                                   {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="role-{{ $role->id }}">
                                                @if($role->name === 'Admin')
                                                    <span class="text-danger"><i class="ph-crown me-1"></i> Administrador</span>
                                                @elseif($role->name === 'Agente')
                                                    <span class="text-primary"><i class="ph-user-gear me-1"></i> Agente</span>
                                                @elseif($role->name === 'Fiscal')
                                                    <span class="text-info"><i class="ph-qr-code me-1"></i> Fiscal</span>
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

                        <div class="alert alert-info">
                            <i class="ph-info me-2"></i>
                            <strong>Dica:</strong> Pode selecionar mais que uma função (ex: um Agente que também valida bilhetes).
                        </div>

                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('users.index') }}" class="btn btn-light">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success ms-2">
                            <i class="ph-check me-2"></i>
                            Criar Utilizador
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar com ajuda -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="ph-shield-check me-2"></i> Níveis de Acesso</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-danger"><i class="ph-crown"></i> Administrador</h6>
                        <small class="text-muted">Acesso total ao sistema</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="ph-user-gear"></i> Agente</h6>
                        <small class="text-muted">Emite bilhetes, vê relatórios</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info"><i class="ph-qr-code"></i> Fiscal</h6>
                        <small class="text-muted">Só valida bilhetes com QR Code</small>
                    </div>
                </div>
            </div>

            <div class="card mt-3 bg-light">
                <div class="card-body text-center">
                    <i class="ph-lock-key ph-3x text-primary mb-3"></i>
                    <p class="mb-2"><strong>Segurança</strong></p>
                    <small class="text-muted">
                        A palavra-passe deve ter no mínimo 8 caracteres.<br>
                        O utilizador receberá o email com os dados de acesso.
                    </small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
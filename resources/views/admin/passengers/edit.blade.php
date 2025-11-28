@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <div class="page-title">
                <h4>
                    <i class="ph-pencil-simple me-2"></i>
                    <span class="fw-semibold">Editar Passageiro</span>
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-light mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('passengers.index') }}">Passageiros</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('passengers.show', $passenger) }}">{{ $passenger->first_name }} {{ $passenger->last_name }}</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </nav>
            </div>
            <div class="my-auto ms-auto">
                <a href="{{ route('passengers.show', $passenger) }}" class="btn btn-light">
                    <i class="ph-x me-2"></i> Cancelar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('passengers.update', $passenger) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-user me-2"></i>
                            Informações Pessoais
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Primeiro Nome <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $passenger->first_name) }}" required autofocus>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Último Nome <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $passenger->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $passenger->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Telefone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $passenger->phone) }}" placeholder="+258 84 123 4567" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                                <select name="document_type" class="form-select @error('document_type') is-invalid @enderror" required>
                                    <option value="">Selecione...</option>
                                    <option value="bi" {{ old('document_type', $passenger->document_type) === 'bi' ? 'selected' : '' }}>BI</option>
                                    <option value="passaporte" {{ old('document_type', $passenger->document_type) === 'passaporte' ? 'selected' : '' }}>Passaporte</option>
                                    <option value="nuit" {{ old('document_type', $passenger->document_type) === 'nuit' ? 'selected' : '' }}>NUIT</option>
                                </select>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Número do Documento <span class="text-danger">*</span></label>
                                <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror"
                                       value="{{ old('document_number', $passenger->document_number) }}" required>
                                @error('document_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nova Palavra-passe</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Deixe em branco para manter a atual">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirmar Palavra-passe</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                       {{ old('is_active', $passenger->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Conta ativa</strong> – o passageiro pode comprar bilhetes
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('passengers.show', $passenger) }}" class="btn btn-light">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="ph-check me-2"></i>
                            Guardar Alterações
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar com Resumo -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Resumo do Passageiro</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 100px; height: 100px; font-size: 40px; background: #e3f2fd;">
                            <i class="ph-user text-primary"></i>
                        </div>
                        <h5 class="mb-1">{{ $passenger->first_name }} {{ $passenger->last_name }}</h5>
                        <p class="text-muted mb-2">{{ $passenger->email }}</p>
                        <span class="badge bg-{{ $passenger->is_active ? 'success' : 'danger' }} bg-opacity-10 text-{{ $passenger->is_active ? 'success' : 'danger' }} fs-sm">
                            {{ $passenger->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>

                    <hr>

                    <div class="row text-start">
                        <div class="col-6">
                            <small class="text-muted">Bilhetes</small>
                            <h5 class="mb-0">{{ $passenger->tickets_count }}</h5>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Total Pago</small>
                            <h5 class="mb-0 text-success">
                                {{ number_format($passenger->tickets()->where('status', 'paid')->sum('price'), 2) }} MT
                            </h5>
                        </div>
                    </div>

                    <hr>

                    <div class="text-start">
                        <small class="text-muted d-block">Registado em</small>
                        <strong>{{ $passenger->created_at->format('d/m/Y \à\s H:i') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Ações Rápidas</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('passengers.show', $passenger) }}" class="list-group-item list-group-item-action">
                        <i class="ph-eye me-2"></i> Ver Perfil Completo
                    </a>
                    <a href="{{ route('tickets.create') }}?passenger_id={{ $passenger->id }}" class="list-group-item list-group-item-action">
                        <i class="ph-plus-circle me-2"></i> Emitir Novo Bilhete
                    </a>
                    <form action="{{ route('passengers.toggle', $passenger) }}" method="POST" class="list-group-item">
                        @csrf
                        <button type="submit" class="d-block w-100 text-start border-0 bg-transparent p-0">
                            <i class="ph-user-switch me-2"></i>
                            {{ $passenger->is_active ? 'Desativar Conta' : 'Ativar Conta' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
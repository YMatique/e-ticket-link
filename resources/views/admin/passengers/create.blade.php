@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <div class="page-title">
                <h4>
                    <i class="ph-user-plus me-2"></i>
                    <span class="fw-semibold">Novo Passageiro</span>
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-light mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('passengers.index') }}">Passageiros</a></li>
                        <li class="breadcrumb-item active">Novo</li>
                    </ol>
                </nav>
            </div>
            <div class="my-auto ms-auto">
                <a href="{{ route('passengers.index') }}" class="btn btn-light">
                    <i class="ph-arrow-left me-2"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('passengers.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-user me-2"></i>
                            Dados do Passageiro
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Primeiro Nome <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}" required autofocus>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Último Nome <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Telefone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" placeholder="+258 84 123 4567" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                                <select name="document_type" class="form-select @error('document_type') is-invalid @enderror" required>
                                    <option value="">Selecione...</option>
                                    <option value="bi" {{ old('document_type') === 'bi' ? 'selected' : '' }}>BI</option>
                                    <option value="passaporte" {{ old('document_type') === 'passaporte' ? 'selected' : '' }}>Passaporte</option>
                                    <option value="nuit" {{ old('document_type') === 'nuit' ? 'selected' : '' }}>NUIT</option>
                                </select>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Número do Documento <span class="text-danger">*</span></label>
                                <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror"
                                       value="{{ old('document_number') }}" required>
                                @error('document_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
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

                        <div class="mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    <strong>Conta ativa por padrão</strong> – pode comprar bilhetes imediatamente
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('passengers.index') }}" class="btn btn-light">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success ms-2">
                            <i class="ph-check me-2"></i>
                            Criar Passageiro
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar com Dicas -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Dicas para Cadastro Rápido</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="ph-check text-success me-2"></i> Use o email real do cliente</li>
                        <li class="mb-2"><i class="ph-check text-success me-2"></i> Telefone deve ser +258 84/85/87 XXX XXXX</li>
                        <li class="mb-2"><i class="ph-check text-success me-2"></i> BI com 12 ou 13 dígitos</li>
                        <li class="mb-2"><i class="ph-check text-success me-2"></i> Após criar, pode emitir bilhete diretamente</li>
                    </ul>

                    <hr>

                    <div class="text-center">
                        <i class="ph-file-csv ph-3x text-muted mb-3 d-block"></i>
                        <p class="mb-2"><strong>Quer importar muitos de uma vez?</strong></p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="ph-upload-simple me-1"></i>
                            Importar via Excel (em breve)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
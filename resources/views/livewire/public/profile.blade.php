<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-4 mb-4">
            <!-- Informações da Conta -->
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="ph-user-circle ph-5x text-primary"></i>
                    </div>
                    <h5 class="mb-1">{{ $account->name }}</h5>
                    <p class="text-muted mb-3">{{ $account->email }}</p>
                    <span class="badge bg-success">
                        <i class="ph-check-circle me-1"></i>
                        Conta Ativa
                    </span>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-chart-bar me-2"></i>
                        Estatísticas
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="ph-ticket text-primary me-2"></i>
                            Total de Bilhetes
                        </span>
                        <strong>{{ $stats['total_tickets'] }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="ph-check-circle text-success me-2"></i>
                            Bilhetes Ativos
                        </span>
                        <strong>{{ $stats['active_tickets'] }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="ph-users text-info me-2"></i>
                            Passageiros
                        </span>
                        <strong>{{ $stats['total_passengers'] }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="ph-calendar text-warning me-2"></i>
                            Membro desde
                        </span>
                        <strong>{{ $stats['member_since'] }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-lg-8">
            <!-- Success Message -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Informações Pessoais -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-user me-2"></i>
                        Informações Pessoais
                    </h6>
                    @if(!$editingInfo)
                        <button type="button" 
                                class="btn btn-sm btn-light" 
                                wire:click="editInfo">
                            <i class="ph-pencil me-1"></i>
                            Editar
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updateInfo">
                        <!-- Nome -->
                        <div class="mb-3">
                            <label class="form-label">Nome completo</label>
                            <div class="form-control-feedback form-control-feedback-start">
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       wire:model="name"
                                       @if(!$editingInfo) disabled @endif>
                                <div class="form-control-feedback-icon">
                                    <i class="ph-user text-muted"></i>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="form-control-feedback form-control-feedback-start">
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       wire:model="email"
                                       @if(!$editingInfo) disabled @endif>
                                <div class="form-control-feedback-icon">
                                    <i class="ph-envelope text-muted"></i>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Telefone -->
                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <div class="form-control-feedback form-control-feedback-start">
                                <input type="tel" 
                                       class="form-control" 
                                       wire:model="phone"
                                       @if(!$editingInfo) disabled @endif>
                                <div class="form-control-feedback-icon">
                                    <i class="ph-phone text-muted"></i>
                                </div>
                            </div>
                        </div>

                        @if($editingInfo)
                            <div class="d-flex gap-2">
                                <button type="submit" 
                                        class="btn btn-primary"
                                        wire:loading.attr="disabled"
                                        wire:target="updateInfo">
                                    <span wire:loading.remove wire:target="updateInfo">
                                        <i class="ph-check me-2"></i>
                                        Salvar Alterações
                                    </span>
                                    <span wire:loading wire:target="updateInfo">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Salvando...
                                    </span>
                                </button>
                                <button type="button" 
                                        class="btn btn-light" 
                                        wire:click="cancelEditInfo">
                                    <i class="ph-x me-2"></i>
                                    Cancelar
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Alterar Senha -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-lock me-2"></i>
                        Segurança
                    </h6>
                    <button type="button" 
                            class="btn btn-sm btn-light" 
                            wire:click="toggleChangePassword">
                        @if($changingPassword)
                            <i class="ph-x me-1"></i>
                            Cancelar
                        @else
                            <i class="ph-key me-1"></i>
                            Alterar Senha
                        @endif
                    </button>
                </div>
                <div class="card-body">
                    @if($changingPassword)
                        <form wire:submit.prevent="updatePassword">
                            <!-- Senha Atual -->
                            <div class="mb-3">
                                <label class="form-label">Senha atual</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           wire:model="current_password"
                                           placeholder="•••••••••••"
                                           required>
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-lock text-muted"></i>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nova Senha -->
                            <div class="mb-3">
                                <label class="form-label">Nova senha</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="password" 
                                           class="form-control @error('new_password') is-invalid @enderror" 
                                           wire:model="new_password"
                                           placeholder="•••••••••••"
                                           required>
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-lock text-muted"></i>
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Mínimo 8 caracteres</small>
                            </div>

                            <!-- Confirmar Nova Senha -->
                            <div class="mb-3">
                                <label class="form-label">Confirmar nova senha</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="password" 
                                           class="form-control" 
                                           wire:model="new_password_confirmation"
                                           placeholder="•••••••••••"
                                           required>
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-lock text-muted"></i>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="btn btn-primary"
                                    wire:loading.attr="disabled"
                                    wire:target="updatePassword">
                                <span wire:loading.remove wire:target="updatePassword">
                                    <i class="ph-check me-2"></i>
                                    Alterar Senha
                                </span>
                                <span wire:loading wire:target="updatePassword">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Alterando...
                                </span>
                            </button>
                        </form>
                    @else
                        <p class="text-muted mb-0">
                            <i class="ph-shield-check text-success me-2"></i>
                            Sua senha está protegida. Clique em "Alterar Senha" para modificá-la.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Zona de Perigo -->
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="ph-warning me-2"></i>
                        Zona de Perigo
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Se desejar, você pode desativar ou excluir permanentemente sua conta.
                    </p>
                    <button type="button" class="btn btn-outline-danger" disabled>
                        <i class="ph-trash me-2"></i>
                        Excluir Conta
                    </button>
                    <small class="d-block text-muted mt-2">
                        Entre em contato com o suporte para excluir sua conta.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="w-100 mx-auto">
    <!-- Login form -->
    <form wire:submit.prevent="login" class="login-form">
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <i class="ph-user-circle ph-4x text-primary"></i>
                    </div>
                    <h5 class="mb-0">Entrar na sua conta</h5>
                    <span class="d-block text-muted">Insira suas credenciais abaixo</span>
                </div>

                <!-- Success Message -->
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ph-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               wire:model.blur="email"
                               placeholder="seu@email.com" 
                               required 
                               autofocus 
                               autocomplete="username">
                        <div class="form-control-feedback-icon">
                            <i class="ph-envelope text-muted"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Palavra-passe</label>
                        <a href="#" class="text-muted small">Esqueceu a palavra-passe?</a>
                    </div>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               wire:model.blur="password"
                               placeholder="•••••••••••" 
                               required 
                               autocomplete="current-password">
                        <div class="form-control-feedback-icon">
                            <i class="ph-lock text-muted"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="remember" 
                               wire:model="remember">
                        <label class="form-check-label" for="remember">
                            Lembrar-me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mb-3">
                    <button type="submit" 
                            class="btn btn-primary w-100" 
                            wire:loading.attr="disabled"
                            wire:target="login">
                        <span wire:loading.remove wire:target="login">
                            <i class="ph-sign-in me-2"></i>
                            Entrar
                        </span>
                        <span wire:loading wire:target="login">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            A entrar...
                        </span>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center text-muted">
                    Não tem conta? <a href="{{ route('account.register') }}" wire:navigate>Criar conta</a>
                </div>
            </div>
        </div>
    </form>

    <style>
        .login-form {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border: 0;
        }
    </style>
</div>
<div class="w-100 mx-auto">
    <!-- Register form -->
    <form class="register-form" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <i class="ph-user-circle-plus ph-4x text-primary"></i>
                    </div>
                    <h5 class="mb-0">Criar nova conta</h5>
                    <span class="d-block text-muted">Preencha os dados abaixo para se registar</span>
                </div>

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Nome completo</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" placeholder="João Silva" required autofocus autocomplete="name">
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
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" placeholder="seu@email.com" required autocomplete="username">
                        <div class="form-control-feedback-icon">
                            <i class="ph-envelope text-muted"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Phone (opcional) -->
                <div class="mb-3">
                    <label class="form-label">Telefone <span class="text-muted">(opcional)</span></label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}"
                            placeholder="+258 84 123 4567" autocomplete="tel">
                        <div class="form-control-feedback-icon">
                            <i class="ph-phone text-muted"></i>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Palavra-passe</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" placeholder="•••••••••••" required autocomplete="new-password">
                        <div class="form-control-feedback-icon">
                            <i class="ph-lock text-muted"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Mínimo 8 caracteres</small>
                </div>

                <!-- Password Confirmation -->
                <div class="mb-3">
                    <label class="form-label">Confirmar palavra-passe</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" placeholder="•••••••••••" required autocomplete="new-password">
                        <div class="form-control-feedback-icon">
                            <i class="ph-lock text-muted"></i>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="accept_terms" name="accept_terms" required>
                        <label class="form-check-label" for="accept_terms">
                            Aceito os <a href="#" target="_blank">Termos e Condições</a> e a <a href="#"
                                target="_blank">Política de Privacidade</a>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ph-user-circle-plus me-2"></i>
                        Criar conta
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center text-muted">
                    Já tem conta? <a href="{{ route('login') }}">Entrar</a>
                </div>
            </div>
        </div>
    </form>
    <style>
        .register-form {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
        }

        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border: 0;
        }
    </style>
</div>

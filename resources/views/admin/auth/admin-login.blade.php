@extends('layouts.admin-auth')

@section('title', 'Login Administrativo')

@section('content')
<!-- Login form -->
<form class="login-form" method="POST" action="{{ route('admin.login.submit') }}">
	@csrf
	
	<div class="card mb-0">
		<div class="card-body">
			<!-- Header -->
			<div class="text-center mb-4">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
						<i class="ph-shield-check ph-3x"></i>
					</div>
				</div>
				<h4 class="mb-0 fw-bold">Painel Administrativo</h4>
				<span class="d-block text-muted mt-1">Acesso restrito a administradores</span>
			</div>

			<!-- Success Message -->
			@if (session('success'))
				<div class="alert alert-success alert-dismissible fade show border-0" role="alert">
					<div class="d-flex align-items-center">
						<i class="ph-check-circle ph-lg me-2"></i>
						<div class="flex-fill">{{ session('success') }}</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

			<!-- Error Message -->
			@if (session('error'))
				<div class="alert alert-danger alert-dismissible fade show border-0" role="alert">
					<div class="d-flex align-items-center">
						<i class="ph-warning-circle ph-lg me-2"></i>
						<div class="flex-fill">{{ session('error') }}</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

			<!-- Warning Info -->
			<div class="alert alert-warning alert-dismissible border-0 mb-3">
				<div class="d-flex">
					<div class="me-2">
						<i class="ph-warning"></i>
					</div>
					<div class="flex-fill">
						<strong>Atenção!</strong> Esta área é restrita a administradores do sistema. Todas as ações são registadas.
					</div>
				</div>
			</div>

			<!-- Email -->
			<div class="mb-3">
				<label class="form-label fw-semibold">
					<i class="ph-envelope me-1"></i>
					Email Administrativo
				</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="email" 
						   class="form-control form-control-lg @error('email') is-invalid @enderror" 
						   name="email" 
						   value="{{ old('email') }}"
						   placeholder="admin@citylink.mz"
						   required
						   autofocus
						   autocomplete="username">
					<div class="form-control-feedback-icon">
						<i class="ph-user-circle-gear text-muted"></i>
					</div>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Password -->
			<div class="mb-3">
				<label class="form-label fw-semibold">
					<i class="ph-lock me-1"></i>
					Palavra-passe
				</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="password" 
						   class="form-control form-control-lg @error('password') is-invalid @enderror" 
						   name="password"
						   placeholder="•••••••••••"
						   required
						   autocomplete="current-password">
					<div class="form-control-feedback-icon">
						<i class="ph-lock-key text-muted"></i>
					</div>
					@error('password')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Remember Me & 2FA -->
			<div class="mb-4">
				<div class="d-flex align-items-center justify-content-between">
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="remember_me" name="remember">
						<label class="form-check-label" for="remember_me">
							Lembrar-me neste dispositivo
						</label>
					</div>
					<a href="#" class="text-muted text-decoration-none small">
						<i class="ph-question me-1"></i>Precisa de ajuda?
					</a>
				</div>
			</div>

			<!-- Submit Button -->
			<div class="mb-3">
				<button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
					<i class="ph-sign-in me-2"></i>
					Entrar no Painel
				</button>
			</div>

			<!-- Security Info -->
			<div class="border-top pt-3 mt-3">
				<div class="d-flex align-items-start text-muted small">
					<i class="ph-shield-check me-2 mt-1"></i>
					<div>
						<strong>Acesso Seguro:</strong> Esta página usa criptografia SSL/TLS. Suas credenciais estão protegidas. IP: <code>{{ request()->ip() }}</code>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- /login form -->

<!-- Additional Info Card -->
<div class="mt-3">
	<div class="alert alert-info border-0 mb-0">
		<div class="d-flex align-items-center">
			<i class="ph-info ph-lg me-2"></i>
			<div class="flex-fill small">
				<strong>Área do Cliente:</strong> Para acessar como cliente, 
				<a href="{{ route('login') }}" class="alert-link fw-semibold">clique aqui</a>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
	/* Gradient background */
	body {
		background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
		min-height: 100vh;
	}
	
	/* Enhanced card styling */
	.card {
		border-radius: 1rem;
		overflow: hidden;
	}
	
	/* Input focus effects */
	.form-control:focus {
		border-color: #667eea;
		box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
	}
	
	/* Button gradient */
	.btn-primary {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border: none;
		transition: all 0.3s ease;
	}
	
	.btn-primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
		background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
	}
	
	/* Alert styling */
	.alert {
		border-radius: 0.75rem;
	}
</style>
@endpush

@push('scripts')
<script>
	// Auto-dismiss alerts after 5 seconds
	document.addEventListener('DOMContentLoaded', function() {
		setTimeout(function() {
			const alerts = document.querySelectorAll('.alert-dismissible');
			alerts.forEach(function(alert) {
				const bsAlert = new bootstrap.Alert(alert);
				bsAlert.close();
			});
		}, 5000);
		
		// Add form validation feedback
		const form = document.querySelector('form');
		form.addEventListener('submit', function() {
			const submitBtn = form.querySelector('button[type="submit"]');
			submitBtn.disabled = true;
			submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Autenticando...';
		});
	});
</script>
@endpush
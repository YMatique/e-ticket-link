@extends('layouts.admin-auth')

@section('title', 'Registar Administrador')

@section('content')
<!-- Register form -->
<form class="login-form" method="POST" action="{{ route('admin.register.submit') }}">
	@csrf
	
	<div class="card mb-0">
		<div class="card-body">
			<!-- Header -->
			<div class="text-center mb-4">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
						<i class="ph-user-circle-plus ph-3x"></i>
					</div>
				</div>
				<h4 class="mb-0 fw-bold">Criar Novo Administrador</h4>
				<span class="d-block text-muted mt-1">Preencha os dados abaixo</span>
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

			<!-- Warning Info -->
			<div class="alert alert-warning alert-dismissible border-0 mb-3">
				<div class="d-flex">
					<div class="me-2">
						<i class="ph-warning"></i>
					</div>
					<div class="flex-fill">
						<strong>Importante!</strong> Este utilizador terá acesso administrativo completo ao sistema.
					</div>
				</div>
			</div>

			<!-- Nome -->
			<div class="mb-3">
				<label class="form-label fw-semibold">
					<i class="ph-user me-1"></i>
					Nome Completo
				</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="text" 
						   class="form-control @error('name') is-invalid @enderror" 
						   name="name" 
						   value="{{ old('name') }}"
						   placeholder="João Silva"
						   required
						   autofocus
						   autocomplete="name">
					<div class="form-control-feedback-icon">
						<i class="ph-identification-card text-muted"></i>
					</div>
					@error('name')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
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
						   class="form-control @error('email') is-invalid @enderror" 
						   name="email" 
						   value="{{ old('email') }}"
						   placeholder="admin@citylink.mz"
						   required
						   autocomplete="username">
					<div class="form-control-feedback-icon">
						<i class="ph-at text-muted"></i>
					</div>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Telefone (opcional) -->
			<div class="mb-3">
				<label class="form-label fw-semibold">
					<i class="ph-phone me-1"></i>
					Telefone <span class="text-muted">(opcional)</span>
				</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="tel" 
						   class="form-control @error('phone') is-invalid @enderror" 
						   name="phone" 
						   value="{{ old('phone') }}"
						   placeholder="+258 84 123 4567"
						   autocomplete="tel">
					<div class="form-control-feedback-icon">
						<i class="ph-device-mobile text-muted"></i>
					</div>
					@error('phone')
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
						   class="form-control @error('password') is-invalid @enderror" 
						   name="password"
						   placeholder="•••••••••••"
						   required
						   autocomplete="new-password">
					<div class="form-control-feedback-icon">
						<i class="ph-lock-key text-muted"></i>
					</div>
					@error('password')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<small class="form-text text-muted">Mínimo 8 caracteres</small>
			</div>

			<!-- Password Confirmation -->
			<div class="mb-3">
				<label class="form-label fw-semibold">
					<i class="ph-lock me-1"></i>
					Confirmar Palavra-passe
				</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="password" 
						   class="form-control @error('password_confirmation') is-invalid @enderror" 
						   name="password_confirmation"
						   placeholder="•••••••••••"
						   required
						   autocomplete="new-password">
					<div class="form-control-feedback-icon">
						<i class="ph-lock-key text-muted"></i>
					</div>
					@error('password_confirmation')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Password Requirements -->
			<div class="alert alert-light border mb-3">
				<div class="text-muted small">
					<div class="fw-semibold mb-2">
						<i class="ph-info me-1"></i>
						Requisitos da palavra-passe:
					</div>
					<ul class="mb-0 ps-3">
						<li>Mínimo de 8 caracteres</li>
						<li>Pelo menos uma letra maiúscula</li>
						<li>Pelo menos uma letra minúscula</li>
						<li>Pelo menos um número</li>
					</ul>
				</div>
			</div>

			<!-- Submit Button -->
			<div class="mb-3">
				<button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
					<i class="ph-user-circle-plus me-2"></i>
					Criar Administrador
				</button>
			</div>

			<!-- Login Link -->
			<div class="text-center border-top pt-3">
				<span class="text-muted">Já tem conta administrativa?</span>
				<a href="{{ route('admin.login') }}" class="fw-semibold ms-1">Fazer login</a>
			</div>
		</div>
	</div>
</form>
<!-- /register form -->
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
	
	/* Login form max-width */
	.login-form {
		max-width: 520px;
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
			submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Criando...';
		});
		
		// Password strength indicator
		const passwordInput = document.querySelector('input[name="password"]');
		const passwordConfirmation = document.querySelector('input[name="password_confirmation"]');
		
		if (passwordInput && passwordConfirmation) {
			passwordConfirmation.addEventListener('input', function() {
				if (this.value && this.value !== passwordInput.value) {
					this.classList.add('is-invalid');
				} else {
					this.classList.remove('is-invalid');
				}
			});
		}
	});
</script>
@endpush

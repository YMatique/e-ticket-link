@extends('layouts.auth')

@section('title', 'Redefinir Palavra-passe')

@section('content')
<!-- Password reset form -->
<form class="reset-form" method="POST" action="{{ route('password.store') }}">
	@csrf
	
	<!-- Password Reset Token -->
	<input type="hidden" name="token" value="{{ $request->route('token') }}">
	
	<div class="card mb-0">
		<div class="card-body">
			<div class="text-center mb-3">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<i class="ph-lock-key ph-4x text-success"></i>
				</div>
				<h5 class="mb-0">Redefinir palavra-passe</h5>
				<span class="d-block text-muted">Digite sua nova palavra-passe</span>
			</div>

			<!-- Email (hidden but required) -->
			<div class="mb-3">
				<label class="form-label">Email</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="email" 
						   class="form-control @error('email') is-invalid @enderror" 
						   name="email" 
						   value="{{ old('email', $request->email) }}"
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

			<!-- New Password -->
			<div class="mb-3">
				<label class="form-label">Nova palavra-passe</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="password" 
						   class="form-control @error('password') is-invalid @enderror" 
						   name="password"
						   placeholder="•••••••••••"
						   required
						   autocomplete="new-password">
					<div class="form-control-feedback-icon">
						<i class="ph-lock text-muted"></i>
					</div>
					@error('password')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<small class="form-text text-muted">Mínimo 8 caracteres</small>
			</div>

			<!-- Confirm Password -->
			<div class="mb-3">
				<label class="form-label">Confirmar palavra-passe</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="password" 
						   class="form-control @error('password_confirmation') is-invalid @enderror" 
						   name="password_confirmation"
						   placeholder="•••••••••••"
						   required
						   autocomplete="new-password">
					<div class="form-control-feedback-icon">
						<i class="ph-lock text-muted"></i>
					</div>
					@error('password_confirmation')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Password Requirements -->
			<div class="alert alert-light border mb-3">
				<div class="text-muted small">
					<div class="fw-semibold mb-2">Requisitos da palavra-passe:</div>
					<ul class="mb-0 ps-3">
						<li>Mínimo de 8 caracteres</li>
						<li>Pelo menos uma letra maiúscula</li>
						<li>Pelo menos uma letra minúscula</li>
						<li>Pelo menos um número</li>
						<li>Pelo menos um caractere especial</li>
					</ul>
				</div>
			</div>

			<!-- Submit Button -->
			<div class="mb-3">
				<button type="submit" class="btn btn-success w-100">
					<i class="ph-check-circle me-2"></i>
					Redefinir palavra-passe
				</button>
			</div>

			<!-- Back to Login -->
			<div class="text-center">
				<a href="{{ route('login') }}">
					<i class="ph-arrow-left me-1"></i>
					Voltar ao login
				</a>
			</div>
		</div>
	</div>
</form>
<!-- /password reset form -->
@endsection

@push('styles')
<style>
	.reset-form {
		width: 100%;
		max-width: 480px;
	}
	
	.card {
		box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
		border: 0;
	}
</style>
@endpush

@push('scripts')
<script>
	// Password strength indicator (opcional)
	document.addEventListener('DOMContentLoaded', function() {
		const passwordInput = document.querySelector('input[name="password"]');
		
		passwordInput.addEventListener('input', function() {
			const password = this.value;
			// Adicionar indicador visual de força da password aqui se necessário
		});
	});
</script>
@endpush

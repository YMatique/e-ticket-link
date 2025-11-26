@extends('layouts.admin-auth')

@section('title', 'Recuperar Palavra-passe')

@section('content')
<!-- Forgot password form -->
<form class="login-form" method="POST" action="{{ route('admin.password.email') }}">
	@csrf
	
	<div class="card mb-0">
		<div class="card-body">
			<!-- Header -->
			<div class="text-center mb-4">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
						<i class="ph-key ph-3x"></i>
					</div>
				</div>
				<h4 class="mb-0 fw-bold">Recuperar Palavra-passe</h4>
				<span class="d-block text-muted mt-1">Digite seu email administrativo</span>
			</div>

			<!-- Success Message -->
			@if (session('status'))
				<div class="alert alert-success alert-dismissible fade show border-0" role="alert">
					<div class="d-flex align-items-center">
						<i class="ph-check-circle ph-lg me-2"></i>
						<div class="flex-fill">{{ session('status') }}</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

			<!-- Info Alert -->
			<div class="alert alert-info border-0 mb-3">
				<div class="d-flex">
					<div class="me-2">
						<i class="ph-info"></i>
					</div>
					<div class="flex-fill">
						Enviaremos um link de recuperação para o seu email. Por razões de segurança, este link será válido por apenas 60 minutos.
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
						<i class="ph-at text-muted"></i>
					</div>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Submit Button -->
			<div class="mb-3">
				<button type="submit" class="btn btn-warning btn-lg w-100 fw-semibold">
					<i class="ph-paper-plane-tilt me-2"></i>
					Enviar Link de Recuperação
				</button>
			</div>

			<!-- Back to Login -->
			<div class="text-center border-top pt-3">
				<a href="{{ route('admin.login') }}" class="text-decoration-none">
					<i class="ph-arrow-left me-1"></i>
					Voltar ao login
				</a>
			</div>
		</div>
	</div>
</form>
<!-- /forgot password form -->
@endsection

@push('styles')
<style>
	body {
		background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
		min-height: 100vh;
	}
	
	.card {
		border-radius: 1rem;
		overflow: hidden;
	}
	
	.form-control:focus {
		border-color: #f59e0b;
		box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.25);
	}
	
	.btn-warning {
		background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
		border: none;
		color: white;
		transition: all 0.3s ease;
	}
	
	.btn-warning:hover {
		transform: translateY(-2px);
		box-shadow: 0 0.5rem 1rem rgba(245, 158, 11, 0.3);
		background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
		color: white;
	}
</style>
@endpush

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const form = document.querySelector('form');
		form.addEventListener('submit', function() {
			const submitBtn = form.querySelector('button[type="submit"]');
			submitBtn.disabled = true;
			submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
		});
	});
</script>
@endpush

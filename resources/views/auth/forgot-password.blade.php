@extends('layouts.auth')

@section('title', 'Recuperar Palavra-passe')

@section('content')
<!-- Password recovery form -->
<form class="recovery-form" method="POST" action="{{ route('password.email') }}">
	@csrf
	
	<div class="card mb-0">
		<div class="card-body">
			<div class="text-center mb-3">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<i class="ph-key ph-4x text-warning"></i>
				</div>
				<h5 class="mb-0">Recuperar palavra-passe</h5>
				<span class="d-block text-muted">Digite seu email para receber instruções</span>
			</div>

			<!-- Session Status -->
			@if (session('status'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<span class="fw-semibold">Sucesso!</span> {{ session('status') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

			<!-- Description -->
			<div class="alert alert-info border-0 mb-3">
				<div class="d-flex">
					<div class="me-2">
						<i class="ph-info"></i>
					</div>
					<div class="flex-fill">
						Enviaremos um link de recuperação para o seu email. Siga as instruções recebidas para redefinir sua palavra-passe.
					</div>
				</div>
			</div>

			<!-- Email -->
			<div class="mb-3">
				<label class="form-label">Email</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="email" 
						   class="form-control @error('email') is-invalid @enderror" 
						   name="email" 
						   value="{{ old('email') }}"
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

			<!-- Submit Button -->
			<div class="mb-3">
				<button type="submit" class="btn btn-warning w-100">
					<i class="ph-paper-plane-tilt me-2"></i>
					Enviar link de recuperação
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
<!-- /password recovery form -->
@endsection

@push('styles')
<style>
	.recovery-form {
		width: 100%;
		max-width: 420px;
	}
	
	.card {
		box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
		border: 0;
	}
</style>
@endpush

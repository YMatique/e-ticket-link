@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<!-- Login form -->
<form class="login-form" method="POST" action="{{ route('login') }}">
	@csrf
	
	<div class="card mb-0">
		<div class="card-body">
			<div class="text-center mb-3">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<i class="ph-bus ph-4x text-primary"></i>
				</div>
				<h5 class="mb-0">Entrar na sua conta</h5>
				<span class="d-block text-muted">Digite suas credenciais abaixo</span>
			</div>

			<!-- Session Status -->
			@if (session('status'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<span class="fw-semibold">Sucesso!</span> {{ session('status') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

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
						<i class="ph-user-circle text-muted"></i>
					</div>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Password -->
			<div class="mb-3">
				<label class="form-label">Palavra-passe</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="password" 
						   class="form-control @error('password') is-invalid @enderror" 
						   name="password"
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
					<input type="checkbox" class="form-check-input" id="remember_me" name="remember">
					<label class="form-check-label" for="remember_me">Lembrar-me</label>
				</div>
			</div>

			<!-- Submit Button -->
			<div class="mb-3">
				<button type="submit" class="btn btn-primary w-100">
					<i class="ph-sign-in me-2"></i>
					Entrar
				</button>
			</div>

			<!-- Links -->
			<div class="text-center text-muted">
				@if (Route::has('password.request'))
					<a href="{{ route('password.request') }}">Esqueceu a palavra-passe?</a>
				@endif
			</div>

			@if (Route::has('register'))
				<div class="text-center text-muted mt-2">
					Não tem conta? <a href="{{ route('register') }}">Criar uma conta</a>
				</div>
			@endif
		</div>
	</div>
</form>
<!-- /login form -->
@endsection

@push('styles')
<style>
	.login-form {
		width: 100%;
		max-width: 420px;
	}
	
	.card {
		box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
		border: 0;
	}
</style>
@endpush

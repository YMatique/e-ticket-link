@extends('layouts.auth')

@section('title', 'Verificar Email')

@section('content')
<!-- Email verification notice -->
<div class="verify-email-card">
	<div class="card mb-0">
		<div class="card-body">
			<div class="text-center mb-3">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<i class="ph-envelope-open ph-4x text-info"></i>
				</div>
				<h5 class="mb-0">Verificar email</h5>
				<span class="d-block text-muted">Confirme seu endereço de email</span>
			</div>

			<!-- Status Message -->
			@if (session('status') == 'verification-link-sent')
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<div class="d-flex">
						<div class="me-2">
							<i class="ph-check-circle"></i>
						</div>
						<div class="flex-fill">
							<span class="fw-semibold">Link enviado!</span><br>
							Um novo link de verificação foi enviado para seu email.
						</div>
					</div>
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
						Obrigado por se registar! Antes de começar, poderia verificar seu endereço de email clicando no link que acabamos de enviar? Se não recebeu o email, teremos prazer em enviar outro.
					</div>
				</div>
			</div>

			<!-- Email Address -->
			<div class="mb-3">
				<div class="d-flex align-items-center bg-light rounded p-3">
					<i class="ph-envelope text-muted me-2"></i>
					<span class="text-muted">{{ auth()->user()->email }}</span>
				</div>
			</div>

			<!-- Resend Button -->
			<form method="POST" action="{{ route('verification.send') }}">
				@csrf
				<div class="mb-3">
					<button type="submit" class="btn btn-info w-100">
						<i class="ph-paper-plane-tilt me-2"></i>
						Reenviar email de verificação
					</button>
				</div>
			</form>

			<!-- Logout -->
			<form method="POST" action="{{ route('logout') }}">
				@csrf
				<div class="text-center">
					<button type="submit" class="btn btn-link text-muted">
						<i class="ph-sign-out me-1"></i>
						Sair
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /email verification notice -->
@endsection

@push('styles')
<style>
	.verify-email-card {
		width: 100%;
		max-width: 480px;
	}
	
	.card {
		box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
		border: 0;
	}
</style>
@endpush

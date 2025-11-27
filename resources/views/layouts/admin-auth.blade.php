<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name') }} - @yield('title', 'Painel Administrativo')</title>

	<!-- Global stylesheets -->
	<link href="{{ asset('template/assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('template/assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('template/html/layout_1/full/assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
	
	@stack('styles')
	
	<style>
		/* Custom styles for admin auth */
		.login-form {
			width: 100%;
			max-width: 480px;
		}
		
		.card {
			box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
			border: 0;
		}
		
		.admin-badge {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 0.35rem 0.75rem;
			border-radius: 0.5rem;
			font-size: 0.75rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}
	</style>
</head>

<body>

	<!-- Main navbar -->
	{{-- <div class="navbar navbar-dark navbar-static py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
		<div class="container-fluid">
			<div class="navbar-brand">
				<a href="{{ route('admin.login') }}" class="d-inline-flex align-items-center">
					<i class="ph-shield-check ph-2x"></i>
					<span class="d-none d-sm-inline-block h-16px ms-3 fw-bold">CityLink Admin</span>
				</a>
			</div>

			<div class="d-flex justify-content-end align-items-center ms-auto">
				<span class="admin-badge">
					<i class="ph-shield-star me-1"></i>
					Administrador
				</span>
			</div>
		</div>
	</div> --}}
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">

				<!-- Content area -->
				<div class="content d-flex justify-content-center align-items-center">

					@yield('content')

				</div>
				<!-- /content area -->


				<!-- Footer -->
				{{-- <div class="navbar navbar-sm navbar-footer border-top">
					<div class="container-fluid">
						<span class="text-muted">
							<i class="ph-shield-check me-1"></i>
							Painel Administrativo - © {{ date('Y') }} <a href="{{ url('/') }}">CityLink e-Ticket</a>
						</span>

						<ul class="nav">
							<li class="nav-item">
								<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-info"></i>
										<span class="d-none d-md-inline-block ms-2">Ajuda</span>
									</div>
								</a>
							</li>
							<li class="nav-item ms-md-1">
								<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-shield-check"></i>
										<span class="d-none d-md-inline-block ms-2">Segurança</span>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div> --}}
				<!-- /footer -->

			</div>
			<!-- /inner content -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

	<!-- Core JS files -->
	<script src="{{ asset('template/assets/demo/demo_configurator.js') }}"></script>
	<script src="{{ asset('template/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('template/html/layout_1/full/assets/js/app.js') }}"></script>
	
	@stack('scripts')

</body>
</html>
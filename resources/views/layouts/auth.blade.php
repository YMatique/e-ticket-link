<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'CityLink e-Ticket') }} - @yield('title', 'Autenticação')</title>

	<!-- Global stylesheets -->
	<link href="{{ asset('template/assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('template/assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('template/html/layout_1/full/assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
	
	@stack('styles')
</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-dark navbar-static py-2">
		<div class="container-fluid">
			<div class="navbar-brand">
				<a href="{{ url('/') }}" class="d-inline-flex align-items-center">
					<i class="ph-bus ph-2x"></i>
					<span class="d-none d-sm-inline-block h-16px ms-3">CityLink e-Ticket</span>
				</a>
			</div>

			<div class="d-flex justify-content-end align-items-center ms-auto">
				<ul class="navbar-nav flex-row">
					<li class="nav-item">
						<a href="{{ url('/') }}" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
							<div class="d-flex align-items-center mx-md-1">
								<i class="ph-house"></i>
								<span class="d-none d-md-inline-block ms-2">Início</span>
							</div>
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
							<div class="d-flex align-items-center mx-md-1">
								<i class="ph-lifebuoy"></i>
								<span class="d-none d-md-inline-block ms-2">Suporte</span>
							</div>
						</a>
					</li>
					
					@guest
						<li class="nav-item">
							<a href="{{ route('register') }}" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
								<div class="d-flex align-items-center mx-md-1">
									<i class="ph-user-circle-plus"></i>
									<span class="d-none d-md-inline-block ms-2">Registar</span>
								</div>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('login') }}" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
								<div class="d-flex align-items-center mx-md-1">
									<i class="ph-user-circle"></i>
									<span class="d-none d-md-inline-block ms-2">Entrar</span>
								</div>
							</a>
						</li>
					@else
						<li class="nav-item">
							<a href="{{ route('dashboard') }}" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
								<div class="d-flex align-items-center mx-md-1">
									<i class="ph-squares-four"></i>
									<span class="d-none d-md-inline-block ms-2">Dashboard</span>
								</div>
							</a>
						</li>
					@endguest
				</ul>
			</div>
		</div>
	</div>
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
				<div class="navbar navbar-sm navbar-footer border-top">
					<div class="container-fluid">
						<span>&copy; {{ date('Y') }} <a href="{{ url('/') }}">CityLink e-Ticket</a> - Sistema de Bilhetes Electrónicos</span>

						<ul class="nav">
							<li class="nav-item">
								<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-lifebuoy"></i>
										<span class="d-none d-md-inline-block ms-2">Suporte</span>
									</div>
								</a>
							</li>
							<li class="nav-item ms-md-1">
								<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-file-text"></i>
										<span class="d-none d-md-inline-block ms-2">Documentação</span>
									</div>
								</a>
							</li>
							<li class="nav-item ms-md-1">
								<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-shield-check"></i>
										<span class="d-none d-md-inline-block ms-2">Privacidade</span>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
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

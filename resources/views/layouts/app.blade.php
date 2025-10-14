<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'CityLink e-Ticket') }} - @yield('title', 'Dashboard')</title>

	<!-- Global stylesheets -->
	<link href="{{ asset('template/assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('template/assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('template/html/layout_3/full/assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
	
	@stack('styles')
</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-dark navbar-expand-lg navbar-static border-bottom border-bottom-white border-opacity-10">
		<div class="container-fluid">
			<div class="d-flex d-lg-none me-2">
				<button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
					<i class="ph-list"></i>
				</button>
			</div>

			<div class="navbar-brand flex-1 flex-lg-0">
				<a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center">
					<i class="ph-bus ph-2x text-white"></i>
					<span class="d-none d-sm-inline-block text-white fw-bold ms-3">CityLink e-Ticket</span>
				</a>
			</div>

			<ul class="nav flex-row">
				<li class="nav-item d-lg-none">
					<a href="#navbar_search" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="collapse">
						<i class="ph-magnifying-glass"></i>
					</a>
				</li>

				<li class="nav-item nav-item-dropdown-lg dropdown">
					<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="dropdown">
						<i class="ph-squares-four"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-scrollable-sm wmin-lg-600 p-0">
						<div class="d-flex align-items-center border-bottom p-3">
							<h6 class="mb-0">Acesso Rápido</h6>
						</div>

						<div class="row row-cols-1 row-cols-sm-2 g-0">
							<div class="col">
								<a href="{{ route('tickets.create') }}" class="dropdown-item text-wrap h-100 align-items-start border-end-sm border-bottom p-3">
									<div>
										<i class="ph-ticket ph-2x text-primary mb-2"></i>
										<div class="fw-semibold my-1">Emitir Bilhete</div>
										<div class="text-muted">Vender bilhete para passageiro</div>
									</div>
								</a>
							</div>

							<div class="col">
								<a href="{{ route('tickets.validate') }}" class="dropdown-item text-wrap h-100 align-items-start border-bottom p-3">
									<div>
										<i class="ph-qr-code ph-2x text-success mb-2"></i>
										<div class="fw-semibold my-1">Validar Bilhete</div>
										<div class="text-muted">Escanear QR Code do bilhete</div>
									</div>
								</a>
							</div>

							<div class="col">
								<a href="{{ route('schedules.create') }}" class="dropdown-item text-wrap h-100 align-items-start border-end-sm rounded-bottom-start p-3">
									<div>
										<i class="ph-calendar-plus ph-2x text-warning mb-2"></i>
										<div class="fw-semibold my-1">Novo Horário</div>
										<div class="text-muted">Criar horário de viagem</div>
									</div>
								</a>
							</div>

							<div class="col">
								<a href="{{ route('reports.index') }}" class="dropdown-item text-wrap h-100 align-items-start rounded-bottom-end p-3">
									<div>
										<i class="ph-chart-line ph-2x text-info mb-2"></i>
										<div class="fw-semibold my-1">Relatórios</div>
										<div class="text-muted">Ver estatísticas e vendas</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</li>
			</ul>

			<div class="navbar-collapse justify-content-center flex-lg-1 order-2 order-lg-1 collapse" id="navbar_search">
				<div class="navbar-search flex-fill position-relative mt-2 mt-lg-0 mx-lg-3">
					<div class="form-control-feedback form-control-feedback-start flex-grow-1">
						<input type="text" class="form-control bg-white bg-opacity-10 border-white border-opacity-10 text-white rounded-pill" placeholder="Pesquisar bilhetes, passageiros...">
						<div class="form-control-feedback-icon text-white">
							<i class="ph-magnifying-glass"></i>
						</div>
					</div>
				</div>
			</div>

			<ul class="nav flex-row justify-content-end order-1 order-lg-2">
				<li class="nav-item ms-lg-2">
					<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="offcanvas" data-bs-target="#notifications">
						<i class="ph-bell"></i>
						<span class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1">3</span>
					</a>
				</li>

				<li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
					<a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
						<div class="status-indicator-container">
							{{-- <div class="d-flex align-items-center justify-center w-32px h-32px rounded-pill  text-primary fw-bold">
								{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
							</div> --}}
                            <img src="{{ asset('template/assets/images/demo/users/face11.jpg') }}" class="w-32px h-32px rounded-pill" alt="">
							<span class="status-indicator bg-success"></span>
							<span class="status-indicator bg-success"></span>
						</div>
						<span class="d-none d-lg-inline-block mx-lg-2">{{ auth()->user()->name }}</span>
					</a>

					<div class="dropdown-menu dropdown-menu-end">
						<a href="#" class="dropdown-item">
							<i class="ph-user-circle me-2"></i>
							Meu Perfil
						</a>
						<a href="{{ route('settings.index') }}" class="dropdown-item">
							<i class="ph-gear me-2"></i>
							Configurações
						</a>
						<div class="dropdown-divider"></div>
						<form method="POST" action="{{ route('logout') }}">
							@csrf
							<button type="submit" class="dropdown-item">
								<i class="ph-sign-out me-2"></i>
								Terminar Sessão
							</button>
						</form>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Breadcrumbs -->
	<div class="page-header page-header-light shadow ">
		<div class="page-header-content d-lg-flex">
			<div class="d-flex">
				<div class="breadcrumb py-2">
					<a href="{{ route('dashboard') }}" class="breadcrumb-item"><i class="ph-house"></i></a>
					@yield('breadcrumbs')
				</div>

				<a href="#breadcrumb_elements" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
					<i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
				</a>
			</div>

			<div class="collapse d-lg-block ms-lg-auto" id="breadcrumb_elements">
				@yield('header-actions')
			</div>
		</div>
	</div>
	<!-- /breadcrumbs -->

	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content d-lg-flex">
			<div class="d-flex">
				<h4 class="page-title mb-0">
					@yield('page-title', 'Dashboard')
				</h4>

				<a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
					<i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
				</a>
			</div>

			<div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
				@yield('page-header-content')
			</div>
		</div>
	</div>
	<!-- /page header -->

	<!-- Page content -->
	<div class="page-content ">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-main sidebar-expand-lg align-self-start">

			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- Sidebar header -->
				<div class="sidebar-section">
					<div class="sidebar-section-body d-flex justify-content-center">
						<h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navegação</h5>

						<div>
							<button type="button" class="btn btn-light btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
								<i class="ph-arrows-left-right"></i>
							</button>

							<button type="button" class="btn btn-light btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
								<i class="ph-x"></i>
							</button>
						</div>
					</div>
				</div>
				<!-- /sidebar header -->


				<!-- Main navigation -->
				<div class="sidebar-section">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Dashboard -->
						<li class="nav-item-header pt-0">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Menu Principal</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item">
							<a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
								<i class="ph-gauge"></i>
								<span>Dashboard</span>
							</a>
						</li>

						<!-- Gestão de Viagens -->
						<li class="nav-item-header">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Gestão de Viagens</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item nav-item-submenu {{ request()->is('routes*') ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link">
								<i class="ph-map-trifold"></i>
								<span>Rotas</span>
							</a>
							<ul class="nav-group-sub collapse {{ request()->is('routes*') ? 'show' : '' }}">
								<li class="nav-item"><a href="{{ route('routes.index') }}" class="nav-link {{ request()->routeIs('routes.index') ? 'active' : '' }}">Lista de Rotas</a></li>
								<li class="nav-item"><a href="{{ route('routes.create') }}" class="nav-link {{ request()->routeIs('routes.create') ? 'active' : '' }}">Nova Rota</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ request()->is('schedules*') ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link">
								<i class="ph-calendar-blank"></i>
								<span>Horários</span>
							</a>
							<ul class="nav-group-sub collapse {{ request()->is('schedules*') ? 'show' : '' }}">
								<li class="nav-item"><a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.index') ? 'active' : '' }}">Lista de Horários</a></li>
								<li class="nav-item"><a href="{{ route('schedules.create') }}" class="nav-link {{ request()->routeIs('schedules.create') ? 'active' : '' }}">Novo Horário</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ request()->is('buses*') ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link">
								<i class="ph-bus"></i>
								<span>Autocarros</span>
							</a>
							<ul class="nav-group-sub collapse {{ request()->is('buses*') ? 'show' : '' }}">
								<li class="nav-item"><a href="{{ route('buses.index') }}" class="nav-link {{ request()->routeIs('buses.index') ? 'active' : '' }}">Lista de Autocarros</a></li>
								<li class="nav-item"><a href="{{ route('buses.create') }}" class="nav-link {{ request()->routeIs('buses.create') ? 'active' : '' }}">Novo Autocarro</a></li>
							</ul>
						</li>

						<!-- Passageiros e Bilhetes -->
						<li class="nav-item-header">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Passageiros & Bilhetes</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item nav-item-submenu {{ request()->is('passengers*') ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link">
								<i class="ph-users"></i>
								<span>Passageiros</span>
							</a>
							<ul class="nav-group-sub collapse {{ request()->is('passengers*') ? 'show' : '' }}">
								<li class="nav-item"><a href="{{ route('passengers.index') }}" class="nav-link {{ request()->routeIs('passengers.index') ? 'active' : '' }}">Lista de Passageiros</a></li>
								<li class="nav-item"><a href="{{ route('passengers.create') }}" class="nav-link {{ request()->routeIs('passengers.create') ? 'active' : '' }}">Novo Passageiro</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ request()->is('tickets*') ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link">
								<i class="ph-ticket"></i>
								<span>Bilhetes</span>
							</a>
							<ul class="nav-group-sub collapse {{ request()->is('tickets*') ? 'show' : '' }}">
								<li class="nav-item"><a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.index') ? 'active' : '' }}">Todos os Bilhetes</a></li>
								<li class="nav-item"><a href="{{ route('tickets.create') }}" class="nav-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}">Emitir Bilhete</a></li>
								<li class="nav-item"><a href="{{ route('tickets.validate') }}" class="nav-link {{ request()->routeIs('tickets.validate') ? 'active' : '' }}">Validar Bilhete</a></li>
							</ul>
						</li>

						<!-- Pagamentos -->
						<li class="nav-item-header">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Financeiro</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item nav-item-submenu {{ request()->is('payments*') ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link">
								<i class="ph-credit-card"></i>
								<span>Pagamentos</span>
							</a>
							<ul class="nav-group-sub collapse {{ request()->is('payments*') ? 'show' : '' }}">
								<li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">Transacções</a></li>
								<li class="nav-item"><a href="{{ route('payments.mpesa') }}" class="nav-link {{ request()->routeIs('payments.mpesa') ? 'active' : '' }}">M-Pesa / e-Mola</a></li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
								<i class="ph-chart-line"></i>
								<span>Relatórios</span>
							</a>
						</li>

						<!-- Localização -->
						<li class="nav-item-header">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Localização</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item">
							<a href="{{ route('provinces.index') }}" class="nav-link {{ request()->routeIs('provinces.*') ? 'active' : '' }}">
								<i class="ph-map-pin"></i>
								<span>Províncias</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('cities.index') }}" class="nav-link {{ request()->routeIs('cities.*') ? 'active' : '' }}">
								<i class="ph-buildings"></i>
								<span>Cidades</span>
							</a>
						</li>

						<!-- Configurações -->
						<li class="nav-item-header">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Sistema</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item nav-item-submenu {{ request()->is('settings*') ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link">
								<i class="ph-gear"></i>
								<span>Configurações</span>
							</a>
							<ul class="nav-group-sub collapse {{ request()->is('settings*') ? 'show' : '' }}">
								<li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Utilizadores</a></li>
								<li class="nav-item"><a href="{{ route('settings.system') }}" class="nav-link {{ request()->routeIs('settings.system') ? 'active' : '' }}">Sistema</a></li>
							</ul>
						</li>

					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content">
				@if(session('success'))
					<div class="alert alert-success alert-dismissible fade show">
						<span class="fw-semibold">Sucesso!</span> {{ session('success') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				@endif

				@if(session('error'))
					<div class="alert alert-danger alert-dismissible fade show">
						<span class="fw-semibold">Erro!</span> {{ session('error') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				@endif

				@if($errors->any())
					<div class="alert alert-danger alert-dismissible fade show">
						<span class="fw-semibold">Erro!</span> Por favor corrija os seguintes erros:
						<ul class="mb-0 mt-2">
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				@endif

				@yield('content')
			</div>
			<!-- /content area -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	<!-- Footer -->
	<div class="navbar navbar-sm navbar-footer border-top">
		<div class="container-fluid">
			<span>&copy; {{ date('Y') }} <a href="#">CityLink e-Ticket</a> - Sistema de Bilhetes Electrónicos</span>

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
			</ul>
		</div>
	</div>
	<!-- /footer -->


	<!-- Notifications -->
	<div class="offcanvas offcanvas-end" tabindex="-1" id="notifications">
		<div class="offcanvas-header py-0">
			<h5 class="offcanvas-title py-3">Notificações</h5>
			<button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
				<i class="ph-x"></i>
			</button>
		</div>

		<div class="offcanvas-body p-0">
			<div class="bg-light fw-medium py-2 px-3">Novas notificações</div>
			<div class="p-3">
				<div class="d-flex align-items-start mb-3">
					<div class="me-3">
						<div class="bg-success bg-opacity-10 text-success rounded-pill p-2">
							<i class="ph-check-circle"></i>
						</div>
					</div>
					<div class="flex-fill">
						<a href="#" class="fw-semibold">Pagamento confirmado</a>
						<div class="text-muted">Bilhete #TKT-20251013-ABC123 pago via M-Pesa</div>
						<div class="fs-sm text-muted mt-1">5 minutos atrás</div>
					</div>
				</div>

				<div class="d-flex align-items-start mb-3">
					<div class="me-3">
						<div class="bg-warning bg-opacity-10 text-warning rounded-pill p-2">
							<i class="ph-warning"></i>
						</div>
					</div>
					<div class="flex-fill">
						<a href="#" class="fw-semibold">Autocarro em manutenção</a>
						<div class="text-muted">Autocarro ABC-1234 necessita revisão</div>
						<div class="fs-sm text-muted mt-1">1 hora atrás</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /notifications -->


    {{-- <script src="{{ asset('template/assets/demo/demo_configurator.js') }}"></script> --}}
    <script src="{{ asset('template/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('template/assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/html/layout_3/full/assets/js/app.js') }}"></script>
	<script src="{{ asset('js/toast.js') }}"></script>
    {{-- <script src="{{ asset('template/assets/demo/pages/dashboard.js') }}"></script> --}}
    
    	<!-- Toast Container -->
	<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
		<!-- Toasts serão inseridos aqui via JavaScript -->
	</div>
	<!-- /toast container -->
	@stack('scripts')

</body>
</html>
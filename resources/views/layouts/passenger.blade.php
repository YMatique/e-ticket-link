<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CityLink e-Ticket' }} - Compre seu bilhete online</title>

    <!-- Global stylesheets -->
    <link href="{{ asset('template/assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/html/layout_3/full/assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    
    @livewireStyles
    @stack('styles')
</head>

<body class="bg-light">

    <!-- Public Navbar -->
    <div class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <div class="navbar-brand">
                <a href="{{ route('public.home') }}" class="d-inline-flex align-items-center">
                    <i class="ph-bus ph-2x text-primary"></i>
                    <span class="fw-bold ms-3 text-primary">CityLink e-Ticket</span>
                </a>
            </div>

            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="ph-list"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ route('public.home') }}" class="nav-link">
                            <i class="ph-house me-1"></i>
                            Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a 
                        href="{{ route('public.my-tickets') }}" 
                        class="nav-link">
                            <i class="ph-ticket me-1"></i>
                            Meus Bilhetes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a 
                        {{-- href="{{ route('public.help') }}"  --}}
                        class="nav-link">
                            <i class="ph-question me-1"></i>
                            Ajuda
                        </a>
                    </li>
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ph-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <i class="ph-gauge me-2"></i>
                                    Dashboard
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="ph-sign-out me-2"></i>
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">
                                <i class="ph-sign-in me-1"></i>
                                Entrar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">
                        <i class="ph-bus me-2"></i>
                        CityLink e-Ticket
                    </h5>
                    <p class="text-white-50">
                        Seu sistema de bilhetes electrónicos para viagens de autocarro em Moçambique.
                    </p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="ph-facebook-logo ph-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="ph-instagram-logo ph-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="ph-whatsapp-logo ph-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="mb-3">Links Rápidos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('public.home') }}" class="text-white-50 text-decoration-none">
                                <i class="ph-caret-right me-1"></i> Comprar Bilhete
                            </a>
                        </li>
                        <li class="mb-2">
                            <a 
                            {{-- href="{{ route('public.my-tickets') }}"  --}}
                            class="text-white-50 text-decoration-none">
                                <i class="ph-caret-right me-1"></i> Meus Bilhetes
                            </a>
                        </li>
                        <li class="mb-2">
                            <a 
                            {{-- href="{{ route('public.help') }}"  --}}
                            class="text-white-50 text-decoration-none">
                                <i class="ph-caret-right me-1"></i> Ajuda
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="ph-caret-right me-1"></i> Termos e Condições
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="mb-3">Contacto</h6>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">
                            <i class="ph-phone me-2"></i>
                            +258 84 123 4567
                        </li>
                        <li class="mb-2">
                            <i class="ph-envelope me-2"></i>
                            info@citylink.co.mz
                        </li>
                        <li class="mb-2">
                            <i class="ph-map-pin me-2"></i>
                            Maputo, Moçambique
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-top border-secondary py-3">
            <div class="container text-center text-white-50">
                <small>&copy; {{ date('Y') }} CityLink e-Ticket. Todos os direitos reservados.</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('template/assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    
    @livewireScripts
    @stack('scripts')

</body>
</html>
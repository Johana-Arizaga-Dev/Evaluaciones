<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Evaluaciones</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
</head>
<body>
    <div id="app">
        @auth
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line"></i> Sistema de Evaluaciones
                </a>

                <div class="navbar-text me-3">
                    <small class="text-light">
                        <i class="fas fa-id-badge me-1"></i>
                        Nivel {{ auth()->user()->nivelJerarquico }}
                    </small>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        @if(auth()->user()->puedeEvaluar())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('evaluaciones.pendientes') }}">
                                <i class="fas fa-clipboard-check"></i> Evaluar
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->puedeCrearFormularios())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('formularios.index') }}">
                                <i class="fas fa-edit"></i> Formularios
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->puedeAdministrar())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> Administrar
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a></li>
                                <li><a class="dropdown-item" href="{{ route('puestos.index') }}">Puestos</a></li>
                                <li><a class="dropdown-item" href="{{ route('areas.index') }}">Áreas</a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                {{ auth()->user()->nombre_empleado }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small>
                                            <i class="fas fa-id-card me-1"></i>
                                            {{ auth()->user()->numero_empleado }}
                                        </small>
                                    </span>
                                </li>
                                <li>
                                    <span class="dropdown-item-text">
                                        <small>
                                            <i class="fas fa-briefcase me-1"></i>
                                            {{ auth()->user()->puesto->nombre_puesto ?? 'Sin puesto' }}
                                        </small>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endauth

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

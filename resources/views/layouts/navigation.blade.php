@php
    $usuario = auth()->user();
    $logo = file_exists(public_path('img/logo-ufpso.png')) ? asset('img/logo-ufpso.png') : null;

    $enlaces = [
        ['ruta' => 'dashboard', 'rutas' => ['dashboard'], 'icono' => 'bi-house-door', 'texto' => 'Inicio'],
        ['ruta' => 'libros.catalogo', 'rutas' => ['libros.catalogo'], 'icono' => 'bi-search', 'texto' => 'Catálogo'],
        ['ruta' => 'reservas.index', 'rutas' => ['reservas.*'], 'icono' => 'bi-bookmark-check', 'texto' => 'Mis reservas'],
        ['ruta' => 'recompensas.index', 'rutas' => ['recompensas.*'], 'icono' => 'bi-gift', 'texto' => 'Recompensas'],
    ];

    if ($usuario->puedeGestionarBiblioteca()) {
        $enlaces[] = ['ruta' => 'libros.index', 'rutas' => ['libros.index', 'libros.create', 'libros.edit'], 'icono' => 'bi-journal-plus', 'texto' => 'Gestionar libros'];
        $enlaces[] = ['ruta' => 'prestamos.index', 'rutas' => ['prestamos.*'], 'icono' => 'bi-arrow-left-right', 'texto' => 'Mostrador'];
    }

    if ($usuario->esSuperadmin()) {
        $enlaces[] = ['ruta' => 'usuarios.index', 'rutas' => ['usuarios.*'], 'icono' => 'bi-people', 'texto' => 'Usuarios'];
    }
@endphp

<header class="ufpso-header">
    <div class="container d-flex align-items-center justify-content-between py-3">

        <a href="{{ route('dashboard') }}" class="ufpso-logo d-flex align-items-center">
            @if ($logo)
                <img src="{{ $logo }}" alt="Universidad Francisco de Paula Santander Ocaña" height="48">
            @else
                <span class="ufpso-logo-texto">
                    <strong>UFPS</strong> Ocaña
                    <small>Universidad Francisco de Paula Santander</small>
                </span>
            @endif
            <span class="ufpso-divisor d-none d-md-inline-block"></span>
            <span class="d-none d-md-inline small">Biblioteca Argemiro Bayona Portillo</span>
        </a>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('recompensas.index') }}" class="ufpso-puntos" title="Tus puntos">
                <i class="bi bi-star-fill"></i> {{ $usuario->puntos_actuales }} pts
            </a>

            <div class="dropdown">
                <button class="btn btn-usuario dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-sm-inline">{{ $usuario->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted">{{ $usuario->email }} · {{ $usuario->rol }}</span></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<nav class="ufpso-barra">
    <div class="container">
        <div class="ufpso-barra-items">
            @foreach ($enlaces as $enlace)
                <a href="{{ route($enlace['ruta']) }}"
                   class="ufpso-barra-item {{ request()->routeIs(...$enlace['rutas']) ? 'activo' : '' }}">
                    <i class="bi {{ $enlace['icono'] }}"></i>
                    <span>{{ $enlace['texto'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>
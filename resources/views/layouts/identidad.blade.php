@php
    $titulos = [
        'login' => 'Iniciar sesión',
        'register' => 'Registro',
        'password.request' => 'Recuperar contraseña',
        'password.reset' => 'Nueva contraseña',
        'password.confirm' => 'Confirmar contraseña',
        'verification.notice' => 'Verificar correo',
        'dashboard' => 'Inicio',
        'libros.catalogo' => 'Catálogo',
        'reservas.index' => 'Mis reservas',
        'recompensas.index' => 'Recompensas',
        'profile.edit' => 'Perfil',
        'libros.index' => 'Gestionar libros',
        'libros.create' => 'Agregar libro',
        'libros.edit' => 'Editar libro',
        'prestamos.index' => 'Mostrador',
        'usuarios.index' => 'Usuarios',
        'usuarios.create' => 'Crear usuario',
    ];

    $pagina = $tituloManual ?? ($titulos[request()->route()?->getName()] ?? null);
@endphp

<title>{{ $pagina ? $pagina . ' · ' : '' }}Biblioteca UFPSO</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
<link rel="icon" type="image/png" sizes="64x64" href="{{ asset('img/favicon.png') }}?v=2">
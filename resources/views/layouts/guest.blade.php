<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @include('layouts.identidad', ['tituloManual' => $attributes->get('titulo')])

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/ufpso.css') }}?v={{ filemtime(public_path('css/ufpso.css')) }}">
    </head>
    <body class="ufpso-login">
        <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 px-3 py-4">

            <a href="/" class="mb-4 text-center text-decoration-none text-white">
                @if (file_exists(public_path('img/logo-ufpso.png')))
                    <img src="{{ asset('img/logo-ufpso.png') }}" alt="Universidad Francisco de Paula Santander Ocaña" height="64">
                @else
                    <div class="ufpso-logo-texto">
                        <strong>UFPS</strong> Ocaña
                        <small>Universidad Francisco de Paula Santander</small>
                    </div>
                @endif
                <div class="mt-2 fw-semibold">Biblioteca Argemiro Bayona Portillo</div>
            </a>

            <div class="ufpso-login-tarjeta">
                {{ $slot }}
            </div>

            <p class="text-white-50 small mt-4 mb-0">Consulta y reserva de libros en línea</p>
        </div>
    </body>
</html>

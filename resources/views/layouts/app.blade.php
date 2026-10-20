<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    (function () {
        const elementos = document.querySelectorAll('[data-cuenta-regresiva]');
        if (!elementos.length) return;

        const inicio = Date.now();
        const base = new Map();
        elementos.forEach(el => base.set(el, parseInt(el.dataset.cuentaRegresiva, 10)));

        const dos = n => String(n).padStart(2, '0');

        function formato(s) {
            const d = Math.floor(s / 86400);
            const h = Math.floor((s % 86400) / 3600);
            const m = Math.floor((s % 3600) / 60);
            const seg = s % 60;
            const hms = `${dos(h)}:${dos(m)}:${dos(seg)}`;
            return d > 0 ? `${d}d ${hms}` : hms;
        }

        function tick() {
            const pasado = Math.floor((Date.now() - inicio) / 1000);
            elementos.forEach(el => {
                const restante = base.get(el) - pasado;
                if (restante <= 0) {
                    el.textContent = el.dataset.textoFin;
                    el.classList.add('text-danger');
                } else {
                    el.textContent = formato(restante);
                    el.classList.toggle('text-danger', restante <= 600); // rojo en los últimos 10 min
                }
            });
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>
    </body>
</html>

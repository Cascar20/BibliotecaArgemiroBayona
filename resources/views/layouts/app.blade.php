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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/ufpso.css') }}?v={{ filemtime(public_path('css/ufpso.css')) }}">
    </head>
    <body>
        @include('layouts.navigation')

        @isset($header)
            <div class="ufpso-banner">
                <div class="container py-4">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <main class="ufpso-contenido">
            {{ $slot }}
        </main>

        <footer class="ufpso-footer">
            <div class="container py-4 d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    <strong>Biblioteca Argemiro Bayona Portillo</strong><br>
                    <span>Universidad Francisco de Paula Santander Ocaña</span>
                </div>
                <div class="small opacity-75 align-self-md-end">Consulta y reserva en línea</div>
            </div>
        </footer>

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
                            el.classList.toggle('text-danger', restante <= 600);
                        }
                    });
                }

                tick();
                setInterval(tick, 1000);
            })();
        </script>
    </body>
</html>
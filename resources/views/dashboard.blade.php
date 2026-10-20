<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inicio
        </h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="p-4 mb-4 rounded text-white d-flex justify-content-between align-items-center"
             style="background-color: var(--ufpso-azul);">
            <div>
                <h1 class="h4 mb-1">Hola, {{ auth()->user()->name }}</h1>
                <p class="mb-0 opacity-75">Biblioteca Argemiro Bayona</p>
            </div>
            <div class="text-end">
                <div class="display-6 fw-bold">{{ auth()->user()->puntos_actuales }} pts</div>
                <a href="{{ route('recompensas.index') }}" class="link-light small">Ir a la tienda</a>
            </div>
        </div>

        @if (auth()->user()->tieneMultaPendiente())
            <div class="alert alert-warning">
                Tienes una multa pendiente por no reclamar una reserva a tiempo. Acércate a la biblioteca para saldarla;
                mientras tanto no puedes hacer nuevas reservas.
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Reservas pendientes</h5>
                        <p class="display-6 mb-2">{{ $reservasPendientes }}</p>
                        <a href="{{ route('reservas.index') }}" class="btn btn-sm text-white"
                           style="background-color: var(--ufpso-vino);">Ver mis reservas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Buscar un libro</h5>
                        <p class="text-muted small">Consulta la disponibilidad y reserva en línea.</p>
                        <a href="{{ route('libros.catalogo') }}" class="btn btn-sm text-white"
                           style="background-color: var(--ufpso-vino);">Ir al catálogo</a>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3">Libros que tengo prestados</h4>
        <table class="table table-striped align-middle mb-5">
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Devolver antes de</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prestamosActivos as $prestamo)
                    <tr>
                        <td>{{ $prestamo->ejemplar->libro->titulo }}</td>
                        <td>{{ $prestamo->fecha_devolucion_esperada->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            @if ($prestamo->puedeRenovar())
                                <form method="POST" action="{{ route('prestamos.renovar', $prestamo) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-secondary">
                                        Renovar ({{ $prestamo->renovaciones }}/{{ \App\Models\Prestamo::MAX_RENOVACIONES }})
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No tienes libros prestados ahora.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h4 class="mb-3">Misiones para ganar puntos</h4>
        <div class="row g-3">
            @foreach ($misiones as $mision)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">{{ $mision->nombre }}</h6>
                            <p class="card-text small text-muted">{{ $mision->descripcion }}</p>
                            <span class="badge bg-warning text-dark">+{{ $mision->puntos_otorgados }} pts</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

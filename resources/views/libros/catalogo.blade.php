<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catálogo de la Biblioteca
        </h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">

        <div class="p-4 mb-4 rounded text-white" style="background-color: var(--ufpso-azul);">
            <h1 class="h3 mb-1">Biblioteca Argemiro Bayona</h1>
            <p class="mb-0 opacity-75">Consulta de disponibilidad y reserva en línea</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-8">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="form-control" placeholder="Buscar por título o autor...">
            </div>
            <div class="col-md-4">
                <button class="btn text-white w-100" style="background-color: var(--ufpso-vino);">
                    Buscar
                </button>
            </div>
        </form>

        <div class="row g-4">
            @forelse ($libros as $libro)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $libro->titulo }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $libro->autor }}</h6>
                            @php $e = $libro->infoEtiqueta(); @endphp
<div class="mb-2">
    <span class="badge bg-{{ $e['color'] }} {{ $e['color'] === 'warning' ? 'text-dark' : '' }}">{{ $e['nombre'] }}</span>
    <small class="text-muted d-block mt-1">{{ $e['regla'] }}</small>
</div>
                            <p class="card-text small flex-grow-1">{{ Str::limit($libro->descripcion, 100) }}</p>

                            @php $disponibles = $libro->ejemplaresDisponibles(); @endphp

                            <div class="mb-3">
                                @if ($disponibles > 0)
                                    <span class="badge bg-success">{{ $disponibles }} disponible(s)</span>
                                @else
                                    <span class="badge bg-secondary">No disponible</span>
                                @endif
                            </div>

                            @if ($disponibles > 0)
                                <form method="POST" action="{{ route('reservas.store') }}">
                                    @csrf
                                    <input type="hidden" name="ejemplar_id" value="{{ $libro->ejemplares->where('estado', 'disponible')->first()->id }}">
                                    <button class="btn w-100 text-white" style="background-color: var(--ufpso-vino);">
                                        Reservar
                                    </button>
                                    <small class="text-muted d-block mt-1">Tendrás 2 horas para reclamarlo.</small>
                                </form>
                            @else
                                <button class="btn btn-outline-secondary w-100" disabled>Sin ejemplares</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No hay libros registrados todavía.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $libros->links() }}
        </div>
    </div>
</x-app-layout>
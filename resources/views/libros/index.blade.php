<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestionar libros</h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Libros registrados</h4>
            <a href="{{ route('libros.create') }}" class="btn text-white" style="background-color: var(--ufpso-vino);">
                + Agregar libro
            </a>
        </div>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Etiqueta</th>
                    <th>Ejemplares</th>
                    <th>Disponibles</th>
                    <th>Agregar copias</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($libros as $libro)
                    <tr>
                        <td>{{ $libro->titulo }}</td>
                        <td>{{ $libro->autor }}</td>
                        @php $e = $libro->infoEtiqueta(); @endphp
<td>
    <span class="badge bg-{{ $e['color'] }} {{ $e['color'] === 'warning' ? 'text-dark' : '' }}">{{ $e['nombre'] }}</span>
</td>
                        <td>{{ $libro->ejemplares_count }}</td>
                        <td>{{ $libro->disponibles_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('libros.ejemplares.store', $libro) }}" class="d-flex gap-1">
                                @csrf
                                <input type="number" name="cantidad" value="1" min="1" max="50"
                                       class="form-control form-control-sm" style="width: 70px;">
                                <button class="btn btn-sm btn-outline-secondary">Agregar</button>
                            </form>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('libros.edit', $libro) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form method="POST" action="{{ route('libros.destroy', $libro) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este libro?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aún no hay libros. Agrega el primero.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $libros->links() }}
    </div>
</x-app-layout>
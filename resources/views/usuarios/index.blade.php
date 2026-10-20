<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuarios y roles</h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Usuarios registrados</h4>
    <a href="{{ route('usuarios.create') }}" class="btn text-white" style="background-color: var(--ufpso-vino);">
        + Crear usuario
    </a>
</div>
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-9">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="form-control" placeholder="Buscar por nombre o correo...">
            </div>
            <div class="col-md-3">
                <button class="btn text-white w-100" style="background-color: var(--ufpso-vino);">Buscar</button>
            </div>
        </form>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol actual</th>
                    <th>Cambiar rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    @php
                        $colores = ['superadmin' => 'danger', 'bibliotecario' => 'primary', 'usuario' => 'secondary'];
                    @endphp
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <span class="badge bg-{{ $colores[$usuario->rol] ?? 'secondary' }}">{{ $usuario->rol }}</span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('usuarios.rol', $usuario) }}" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="rol" class="form-select form-select-sm" style="max-width: 180px;">
                                    @foreach (['usuario', 'bibliotecario', 'superadmin'] as $rol)
                                        <option value="{{ $rol }}" @selected($usuario->rol === $rol)>{{ $rol }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-secondary">Guardar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $usuarios->links() }}
    </div>
</x-app-layout>
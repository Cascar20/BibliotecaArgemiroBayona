<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear usuario</h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">
        <form method="POST" action="{{ route('usuarios.store') }}" class="card card-body shadow-sm">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contraseña (mínimo 8 caracteres)</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select">
                    @foreach (['usuario' => 'Usuario', 'bibliotecario' => 'Bibliotecario', 'superadmin' => 'Superadmin'] as $valor => $etiqueta)
                        <option value="{{ $valor }}" @selected(old('rol', 'usuario') === $valor)>{{ $etiqueta }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn text-white" style="background-color: var(--ufpso-vino);">Crear usuario</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
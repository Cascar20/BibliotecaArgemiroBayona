@php $l = $libro ?? null; @endphp
@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@foreach ([['titulo', 'Título', true], ['autor', 'Autor', true], ['isbn', 'ISBN', false], ['categoria', 'Categoría', false]] as [$campo, $etiqueta, $requerido])
    <div class="mb-3">
        <label class="form-label">{{ $etiqueta }}</label>
        <input type="text" name="{{ $campo }}" value="{{ old($campo, $l?->$campo) }}"
               class="form-control @error($campo) is-invalid @enderror" @if($requerido) required @endif>
        @error($campo)<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
@endforeach

<div class="mb-3">
    <label class="form-label">Etiqueta de préstamo</label>
    <select name="etiqueta" class="form-select">
        @foreach (\App\Models\Libro::ETIQUETAS as $clave => $info)
            <option value="{{ $clave }}" @selected(old('etiqueta', $l?->etiqueta ?? 'rojo') === $clave)>
                {{ $info['nombre'] }} — {{ $info['regla'] }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" rows="3" class="form-control">{{ old('descripcion', $l?->descripcion) }}</textarea>
</div>

@if (!$l)
    <div class="mb-3">
        <label class="form-label">Cantidad de ejemplares (copias físicas)</label>
        <input type="number" name="cantidad" min="1" max="50" value="{{ old('cantidad', 1) }}"
               class="form-control @error('cantidad') is-invalid @enderror" required>
        @error('cantidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
@endif

<div class="d-flex gap-2">
    <button class="btn text-white" style="background-color: var(--ufpso-vino);">Guardar</button>
    <a href="{{ route('libros.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
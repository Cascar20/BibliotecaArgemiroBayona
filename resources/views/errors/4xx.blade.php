<x-guest-layout :titulo="'Error ' . $exception->getStatusCode()">
    <div class="text-center">
        <div class="display-4 fw-bold" style="color: var(--ufpso-rojo);">{{ $exception->getStatusCode() }}</div>
        <p class="mt-2 mb-4">
            @if ($exception->getStatusCode() === 404)
                La página que buscas no existe.
            @else
                {{ $exception->getMessage() ?: 'No tienes permiso para acceder a esta sección.' }}
            @endif
        </p>
        <a href="{{ url('/') }}" class="btn text-white" style="background-color: var(--ufpso-rojo);">Volver al inicio</a>
    </div>
</x-guest-layout>
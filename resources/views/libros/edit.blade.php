<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar libro</h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">
        <form method="POST" action="{{ route('libros.update', $libro) }}" class="card card-body shadow-sm">
            @method('PUT')
            @include('libros._form')
        </form>
    </div>
</x-app-layout>
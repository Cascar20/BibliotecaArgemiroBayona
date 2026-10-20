<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Reservas
        </h2>
    </x-slot>

    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Fecha de reserva</th>
                    <th>Fecha límite</th>
                    <th>Tiempo restante</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->ejemplar->libro->titulo }}</td>
                        <td>{{ $reserva->fecha_reserva->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reserva->fecha_limite)->format('d/m/Y H:i') }}</td>
                        <td>
                        @if ($reserva->estado === 'pendiente')
                        <x-cuenta-regresiva :segundos="now()->diffInSeconds($reserva->fecha_limite, false)" />
                        @else
                             —
                        @endif
                        </td>
                        <td>
                            @php
                                $colores = [
                                    'pendiente' => 'warning',
                                    'entregada' => 'success',
                                    'vencida' => 'secondary',
                                    'cancelada' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $colores[$reserva->estado] ?? 'secondary' }}">
                                {{ ucfirst($reserva->estado) }}
                            </span>
                        </td>
                        <td>
                            @if ($reserva->estado === 'pendiente')
                                <form method="POST" action="{{ route('reservas.cancelar', $reserva) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-danger">Cancelar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No tienes reservas todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout> 
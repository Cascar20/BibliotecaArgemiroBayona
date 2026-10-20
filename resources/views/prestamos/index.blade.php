<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mostrador</h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- 1. Reservas por entregar --}}
        <h4 class="mb-3">Reservas por entregar</h4>
        <table class="table table-striped align-middle mb-5">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Etiqueta</th>
                    <th>Reclamar antes de</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservas as $reserva)
                    @php
                        $libro = $reserva->ejemplar->libro;
                        $e = $libro->infoEtiqueta();
                        $vencida = $reserva->fecha_limite->isPast();
                    @endphp
                    <tr>
                        <td>{{ $reserva->user->name }}</td>
                        <td>
                            {{ $libro->titulo }}
                            <small class="text-muted d-block">{{ $reserva->ejemplar->codigo_inventario }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $e['color'] }} {{ $e['color'] === 'warning' ? 'text-dark' : '' }}">{{ $e['nombre'] }}</span>
                        </td>
                        <td>
                            {{ $reserva->fecha_limite->format('d/m H:i') }}
                            <div class="small">
                                <x-cuenta-regresiva :segundos="now()->diffInSeconds($reserva->fecha_limite, false)" />
                            </div>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('prestamos.entregar', $reserva) }}"
                                  class="d-flex gap-2 align-items-center justify-content-end">
                                @csrf
                                @if ($libro->etiqueta === 'verde')
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="externo_especial" value="1" id="ext{{ $reserva->id }}">
                                        <label class="form-check-label small" for="ext{{ $reserva->id }}">Externo especial</label>
                                    </div>
                                    <input type="number" name="dias_especial" value="3" min="1" max="15"
                                           class="form-control form-control-sm" style="width: 65px;" title="Días de préstamo">
                                @endif
                                <button class="btn btn-sm text-white" style="background-color: var(--ufpso-vino);"
                                        @disabled($vencida)>Entregar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No hay reservas pendientes.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- 2. Préstamos activos --}}
        <h4 class="mb-3">Préstamos activos</h4>
        <table class="table table-striped align-middle mb-5">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Devolver antes de</th>
                    <th>Renovaciones</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prestamos as $prestamo)
                    <tr>
                        <td>{{ $prestamo->user->name }}</td>
                        <td>{{ $prestamo->ejemplar->libro->titulo }}</td>
                        <td>
                            {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y H:i') }}
                            @if ($prestamo->externo_especial)
                                <span class="badge bg-info text-dark">Externo especial</span>
                            @endif
                            <div class="small">
                                <x-cuenta-regresiva :segundos="now()->diffInSeconds($prestamo->fecha_devolucion_esperada, false)" fin="Atrasado" />
                            </div>
                        </td>
                        <td>{{ $prestamo->renovaciones }}/{{ \App\Models\Prestamo::MAX_RENOVACIONES }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('prestamos.devolver', $prestamo) }}"
                                  onsubmit="return confirm('¿Registrar la devolución?');">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-success">Registrar devolución</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No hay préstamos activos.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- 3. Canjes por entregar --}}
        <h4 class="mb-3">Canjes de la tienda por entregar</h4>
        <table class="table table-striped align-middle mb-5">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Recompensa</th>
                    <th>Puntos</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($canjes as $canje)
                    <tr>
                        <td>{{ $canje->user->name }}</td>
                        <td>{{ $canje->recompensa->nombre }}</td>
                        <td>{{ $canje->puntos_gastados }}</td>
                        <td>{{ $canje->fecha->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('canjes.entregar', $canje) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-success">Marcar entregado</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No hay canjes pendientes.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- 4. Multas pendientes --}}
        <h4 class="mb-3">Multas pendientes</h4>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Motivo</th>
                    <th>Puntos descontados</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($multas as $multa)
                    <tr>
                        <td>{{ $multa->user->name }}</td>
                        <td>{{ $multa->motivo }}</td>
                        <td>{{ $multa->puntos_descontados }}</td>
                        <td>{{ $multa->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('multas.saldar', $multa) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-secondary">Saldar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No hay multas pendientes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
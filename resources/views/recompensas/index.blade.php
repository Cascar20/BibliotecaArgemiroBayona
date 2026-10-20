<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tienda de Recompensas
        </h2>
    </x-slot>

    <div class="container py-4" style="--ufpso-vino: #8b1e2b; --ufpso-azul: #0d1b2a;">

        <div class="p-4 mb-4 rounded text-white d-flex justify-content-between align-items-center"
             style="background-color: var(--ufpso-azul);">
            <div>
                <h1 class="h4 mb-0">Tus puntos</h1>
                <p class="mb-0 opacity-75">Canjéalos por recompensas</p>
            </div>
            <div class="display-6 fw-bold">{{ auth()->user()->puntos_actuales }} pts</div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4 mb-5">
            @forelse ($recompensas as $recompensa)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $recompensa->nombre }}</h5>
                            <p class="card-text small flex-grow-1">{{ $recompensa->descripcion }}</p>
                            <p class="mb-2">
                                <span class="badge bg-warning text-dark">{{ $recompensa->costo_puntos }} pts</span>
                                <span class="badge bg-light text-dark border">Stock: {{ $recompensa->stock }}</span>
                            </p>
                            <form method="POST" action="{{ route('recompensas.canjear', $recompensa) }}"
                                  onsubmit="return confirm('¿Canjear {{ $recompensa->nombre }} por {{ $recompensa->costo_puntos }} puntos?');">
                                @csrf
                                <button class="btn w-100 text-white" style="background-color: var(--ufpso-vino);"
                                        @if(auth()->user()->puntos_actuales < $recompensa->costo_puntos) disabled @endif>
                                    Canjear
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No hay recompensas disponibles todavía.</p>
            @endforelse
        </div>

        <h4 class="mb-3">Mis canjes</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Recompensa</th>
                    <th>Puntos gastados</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($misCanjes as $canje)
                    <tr>
                        <td>{{ $canje->recompensa->nombre }}</td>
                        <td>{{ $canje->puntos_gastados }}</td>
                        <td>{{ $canje->fecha->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $canje->estado === 'entregado' ? 'success' : 'warning' }}">
                                {{ ucfirst($canje->estado) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aún no has canjeado nada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
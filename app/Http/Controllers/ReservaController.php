<?php

namespace App\Http\Controllers;

use App\Models\Ejemplar;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::where('user_id', auth()->id())
            ->with('ejemplar.libro')
            ->latest()
            ->get();

        return view('reservas.index', compact('reservas'));
    }

    public function store(Request $request)
{
    $request->validate([
        'ejemplar_id' => 'required|exists:ejemplares,id',
    ]);

    $user = auth()->user();

    if ($user->tieneMultaPendiente()) {
        return back()->with('error', 'Tienes una multa pendiente. Acércate a la biblioteca para saldarla y poder reservar de nuevo.');
    }

    return DB::transaction(function () use ($request, $user) {
        $ejemplar = Ejemplar::whereKey($request->ejemplar_id)->lockForUpdate()->firstOrFail();

        if ($ejemplar->estado !== 'disponible') {
            return back()->with('error', 'Este ejemplar ya no está disponible.');
        }

        Reserva::create([
            'user_id' => $user->id,
            'ejemplar_id' => $ejemplar->id,
            'fecha_limite' => now()->addHours(2),
            'estado' => 'pendiente',
        ]);

        $ejemplar->update(['estado' => 'reservado']);

        return back()->with('success', 'Reserva creada. Tienes 2 horas para reclamarla en la biblioteca o recibirás una multa.');
    });
}

    public function cancelar(Reserva $reserva)
    {
        if ($reserva->user_id !== auth()->id()) {
            abort(403);
        }

        $reserva->update(['estado' => 'cancelada']);
        $reserva->ejemplar->update(['estado' => 'disponible']);

        return back()->with('success', 'Reserva cancelada.');
    }
}
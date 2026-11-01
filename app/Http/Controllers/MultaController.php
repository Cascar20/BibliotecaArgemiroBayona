<?php

namespace App\Http\Controllers;

use App\Models\Multa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MultaController extends Controller
{
    public function saldar(Multa $multa)
    {
        $multa->update(['estado' => 'saldada']);

        return back()->with('success', "Multa de {$multa->user->name} saldada.");
    }

    public function store(Request $request)
{
    $datos = $request->validate([
        'user_id' => 'required|exists:users,id',
        'motivo' => 'required|string|max:255',
        'puntos' => 'required|integer|min:0|max:500',
    ]);

    $user = User::findOrFail($datos['user_id']);

    if ($user->rol !== 'usuario') {
        return back()->with('error', 'Solo se puede multar a usuarios.');
    }

    DB::transaction(function () use ($user, $datos) {
        Multa::create([
            'user_id' => $user->id,
            'motivo' => $datos['motivo'],
            'puntos_descontados' => $datos['puntos'],
        ]);

        $user->update([
            'puntos_actuales' => max(0, $user->puntos_actuales - $datos['puntos']),
        ]);
    });

    return back()->with('success', "Multa registrada a {$user->name}.");
}
}
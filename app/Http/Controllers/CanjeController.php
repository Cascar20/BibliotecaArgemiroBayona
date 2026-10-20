<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanjeController extends Controller
{
    public function index()
    {
        $recompensas = Recompensa::where('activa', true)->where('stock', '>', 0)->get();
        $misCanjes = Canje::where('user_id', auth()->id())->with('recompensa')->latest()->get();

        return view('recompensas.index', compact('recompensas', 'misCanjes'));
    }

    public function canjear(Request $request, Recompensa $recompensa)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($user, $recompensa) {
            // Bloquea la fila para evitar canjes dobles simultáneos
            $recompensa = Recompensa::where('id', $recompensa->id)->lockForUpdate()->first();

            if ($recompensa->stock <= 0) {
                return back()->with('error', 'Esta recompensa ya no tiene stock.');
            }

            if ($user->puntos_actuales < $recompensa->costo_puntos) {
                return back()->with('error', 'No tienes puntos suficientes para esta recompensa.');
            }

            $user->decrement('puntos_actuales', $recompensa->costo_puntos);
            $recompensa->decrement('stock');

            Canje::create([
                'user_id' => $user->id,
                'recompensa_id' => $recompensa->id,
                'puntos_gastados' => $recompensa->costo_puntos,
                'estado' => 'pendiente',
            ]);

            return back()->with('success', "¡Canjeaste {$recompensa->nombre}! Pasa por bibliotecología a recogerlo.");
        });
    }

     public function marcarEntregado(Canje $canje)
{
    $canje->update(['estado' => 'entregado']);

    return back()->with('success', 'Canje marcado como entregado.');
}   
}
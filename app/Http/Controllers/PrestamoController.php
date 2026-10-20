<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Services\MisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    public function __construct(protected MisionService $misionService)
    {
    }

    public function index()
    {
        $reservas = Reserva::with('user', 'ejemplar.libro')
            ->where('estado', 'pendiente')
            ->orderBy('fecha_limite')
            ->get();

        $prestamos = Prestamo::with('user', 'ejemplar.libro')
            ->whereNull('fecha_devolucion_real')
            ->orderBy('fecha_devolucion_esperada')
            ->get();

        $canjes = Canje::with('user', 'recompensa')
            ->where('estado', 'pendiente')
            ->orderBy('fecha')
            ->get();

        $multas = Multa::with('user')
            ->where('estado', 'pendiente')
            ->latest()
            ->get();

        return view('prestamos.index', compact('reservas', 'prestamos', 'canjes', 'multas'));
    }

    public function entregar(Request $request, Reserva $reserva)
    {
        if ($reserva->estado !== 'pendiente') {
            return back()->with('error', 'Esta reserva ya no está pendiente.');
        }

        if ($reserva->fecha_limite->isPast()) {
            return back()->with('error', 'La reserva venció (pasaron las 2 horas); se generará la multa automáticamente.');
        }

        $request->validate(['dias_especial' => 'nullable|integer|min:1|max:15']);

        $libro = $reserva->ejemplar->libro;

        // Préstamo externo especial: solo etiqueta verde y si el bibliotecario lo autoriza
        $externoEspecial = $libro->etiqueta === 'verde' && $request->boolean('externo_especial');
        $dias = $externoEspecial ? (int) ($request->dias_especial ?? 3) : null;

        DB::transaction(function () use ($reserva, $libro, $externoEspecial, $dias) {
            Prestamo::create([
                'user_id' => $reserva->user_id,
                'ejemplar_id' => $reserva->ejemplar_id,
                'reserva_id' => $reserva->id,
                'fecha_devolucion_esperada' => $libro->fechaDevolucion($dias),
                'externo_especial' => $externoEspecial,
            ]);

            $reserva->update(['estado' => 'entregada']);
            $reserva->ejemplar->update(['estado' => 'prestado']);
        });

        return back()->with('success', 'Préstamo registrado.');
    }

    public function devolver(Prestamo $prestamo)
    {
        if ($prestamo->fecha_devolucion_real) {
            return back()->with('error', 'Este préstamo ya fue devuelto.');
        }

        $prestamo->update(['fecha_devolucion_real' => now()]);
        $prestamo->ejemplar->update(['estado' => 'disponible']);

        $this->misionService->evaluarAlDevolver($prestamo);

        return back()->with('success', 'Devolución registrada. Puntos otorgados si aplica.');
    }

    // Lo puede pedir el dueño del préstamo o el personal de la biblioteca
    public function renovar(Prestamo $prestamo)
    {
        $user = auth()->user();

        if ($prestamo->user_id !== $user->id && !$user->puedeGestionarBiblioteca()) {
            abort(403);
        }

        if (!$prestamo->puedeRenovar()) {
            return back()->with('error', 'No se puede renovar: solo etiqueta roja, máximo 3 renovaciones y antes de que venza.');
        }

        $prestamo->update([
            'fecha_devolucion_esperada' => now()->addDays(8),
            'renovaciones' => $prestamo->renovaciones + 1,
        ]);

        return back()->with('success', 'Préstamo renovado por 8 días más.');
    }
}
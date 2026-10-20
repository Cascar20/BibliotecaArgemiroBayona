<?php

namespace App\Services;

use App\Models\Mision;
use App\Models\MisionCompletada;
use App\Models\Prestamo;
use App\Models\User;

class MisionService
{
    public function otorgar(User $user, string $codigo): void
    {
        $mision = Mision::where('codigo', $codigo)->where('activa', true)->first();

        if (!$mision) {
            return;
        }

        MisionCompletada::create([
            'user_id' => $user->id,
            'mision_id' => $mision->id,
        ]);

        $user->increment('puntos_actuales', $mision->puntos_otorgados);
    }

    // Se llama cada vez que se registra la devolución de un préstamo
    public function evaluarAlDevolver(Prestamo $prestamo): void
    {
        $user = $prestamo->user;

        // Misión: devolver a tiempo
        if ($prestamo->fueATiempo()) {
            $this->otorgar($user, 'devolucion_a_tiempo');
        }

        $totalPrestamos = Prestamo::where('user_id', $user->id)
            ->whereNotNull('fecha_devolucion_real')
            ->count();

        // Misión: primer préstamo completado
        if ($totalPrestamos === 1) {
            $this->otorgar($user, 'primer_prestamo');
        }

        // Misión: 5 préstamos completados
        if ($totalPrestamos === 5) {
            $this->otorgar($user, 'cinco_prestamos');
        }
    }
}
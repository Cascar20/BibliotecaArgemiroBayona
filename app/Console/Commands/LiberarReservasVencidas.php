<?php

namespace App\Console\Commands;

use App\Models\Multa;
use App\Models\Reserva;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LiberarReservasVencidas extends Command
{
    protected $signature = 'reservas:liberar-vencidas';
    protected $description = 'Libera las reservas no reclamadas en 2 horas y genera la multa';

    private const PUNTOS_MULTA = 10;

    public function handle(): void
    {
        $vencidas = Reserva::with(['ejemplar', 'user'])
            ->where('estado', 'pendiente')
            ->where('fecha_limite', '<', now())
            ->get();

        foreach ($vencidas as $reserva) {
            DB::transaction(function () use ($reserva) {
                $reserva->update(['estado' => 'vencida']);
                $reserva->ejemplar->update(['estado' => 'disponible']);

                Multa::create([
                    'user_id' => $reserva->user_id,
                    'reserva_id' => $reserva->id,
                    'motivo' => 'No reclamó la reserva dentro de las 2 horas.',
                    'puntos_descontados' => self::PUNTOS_MULTA,
                ]);

                $user = $reserva->user;
                $user->update([
                    'puntos_actuales' => max(0, $user->puntos_actuales - self::PUNTOS_MULTA),
                ]);
            });
        }

        $this->info("Reservas vencidas procesadas: {$vencidas->count()}.");
    }
}
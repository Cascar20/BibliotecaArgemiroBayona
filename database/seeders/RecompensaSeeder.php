<?php

namespace Database\Seeders;

use App\Models\Recompensa;
use Illuminate\Database\Seeder;

class RecompensaSeeder extends Seeder
{
    public function run(): void
    {
        $recompensas = [
            // [nombre, descripción, costo en puntos, stock]
            ['Lapicero UFPSO', 'Lapicero institucional', 30, 50],
            ['Cuaderno institucional', 'Cuaderno rayado con el logo de la universidad', 80, 30],
            ['Gorra UFPSO', 'Gorra bordada con el escudo de la universidad', 150, 20],
            ['Termo UFPSO', 'Termo con el logo de la universidad', 250, 10],
            ['Camiseta UFPSO', 'Camiseta institucional', 300, 10],
            ['Morral UFPSO', 'Morral institucional', 400, 5],
        ];

        foreach ($recompensas as [$nombre, $descripcion, $costo, $stock]) {
            Recompensa::firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $descripcion, 'costo_puntos' => $costo, 'stock' => $stock, 'activa' => true]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Mision;
use Illuminate\Database\Seeder;

class MisionSeeder extends Seeder
{
    public function run(): void
    {
        $misiones = [
            ['codigo' => 'primer_prestamo', 'nombre' => 'Primer préstamo', 'descripcion' => 'Completa tu primer préstamo en la biblioteca', 'puntos_otorgados' => 50],
            ['codigo' => 'devolucion_a_tiempo', 'nombre' => 'Puntual', 'descripcion' => 'Devuelve un libro antes o en la fecha límite', 'puntos_otorgados' => 20],
            ['codigo' => 'cinco_prestamos', 'nombre' => 'Lector frecuente', 'descripcion' => 'Completa 5 préstamos', 'puntos_otorgados' => 100],
        ];

        foreach ($misiones as $mision) {
            Mision::updateOrCreate(['codigo' => $mision['codigo']], $mision);
        }
    }
}
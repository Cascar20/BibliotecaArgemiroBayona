<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            // [nombre, correo, rol, puntos]
            ['Bibliotecario de prueba', 'bibliotecario@biblioteca.test', 'bibliotecario', 0],
            ['Estudiante Uno', 'estudiante1@biblioteca.test', 'usuario', 100],
            ['Estudiante Dos', 'estudiante2@biblioteca.test', 'usuario', 0],
            ['Estudiante Tres', 'estudiante3@biblioteca.test', 'usuario', 300],
        ];

        foreach ($usuarios as [$nombre, $correo, $rol, $puntos]) {
            User::firstOrCreate(
                ['email' => $correo],
                ['name' => $nombre, 'password' => 'password', 'rol' => $rol, 'puntos_actuales' => $puntos]
            );
        }
    }
}
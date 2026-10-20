<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Primero pasamos la columna de enum a texto, para poder usar valores nuevos
        Schema::table('users', function (Blueprint $table) {
            $table->string('rol', 20)->default('usuario')->change();
        });

        // Luego convertimos los roles viejos
        DB::table('users')->where('rol', 'administrador')->update(['rol' => 'superadmin']);
        DB::table('users')->whereIn('rol', ['estudiante', 'docente'])->update(['rol' => 'usuario']);
    }

    public function down(): void
    {
        DB::table('users')->where('rol', 'superadmin')->update(['rol' => 'administrador']);
        DB::table('users')->whereIn('rol', ['bibliotecario', 'usuario'])->update(['rol' => 'estudiante']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['administrador', 'estudiante', 'docente'])->default('estudiante')->change();
        });
    }
};
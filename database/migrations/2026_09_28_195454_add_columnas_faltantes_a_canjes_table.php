<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('canjes', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('recompensa_id')->after('user_id')->constrained('recompensas')->cascadeOnDelete();
            $table->integer('puntos_gastados')->after('recompensa_id');
            $table->enum('estado', ['pendiente', 'entregado', 'cancelado'])->default('pendiente')->after('puntos_gastados');
            $table->dateTime('fecha')->useCurrent()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('canjes', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'recompensa_id', 'puntos_gastados', 'estado', 'fecha']);
        });
    }
};

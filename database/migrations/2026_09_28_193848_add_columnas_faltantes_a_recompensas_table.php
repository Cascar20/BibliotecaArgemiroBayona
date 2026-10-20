<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('canjes', function (Blueprint $table) {
            if (!Schema::hasColumn('canjes', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('canjes', 'recompensa_id')) {
                $table->foreignId('recompensa_id')->after('user_id')->constrained('recompensas')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('canjes', 'puntos_gastados')) {
                $table->integer('puntos_gastados')->after('recompensa_id');
            }
            if (!Schema::hasColumn('canjes', 'estado')) {
                $table->enum('estado', ['pendiente', 'entregado', 'cancelado'])->default('pendiente')->after('puntos_gastados');
            }
            if (!Schema::hasColumn('canjes', 'fecha')) {
                $table->dateTime('fecha')->useCurrent()->after('estado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('canjes', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'recompensa_id', 'puntos_gastados', 'estado', 'fecha']);
        });
    }
};
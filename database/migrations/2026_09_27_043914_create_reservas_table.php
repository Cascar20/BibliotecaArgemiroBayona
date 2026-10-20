<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ejemplar_id')->constrained('ejemplares')->cascadeOnDelete();
            $table->dateTime('fecha_reserva')->useCurrent();
            $table->dateTime('fecha_limite'); // hasta cuándo puede recogerlo
            $table->enum('estado', ['pendiente', 'entregada', 'vencida', 'cancelada'])
                  ->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
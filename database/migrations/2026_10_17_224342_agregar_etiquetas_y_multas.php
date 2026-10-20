<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->string('etiqueta', 10)->default('rojo')->after('categoria');
        });

        Schema::table('prestamos', function (Blueprint $table) {
            $table->unsignedTinyInteger('renovaciones')->default(0);
            $table->boolean('externo_especial')->default(false);
        });

        Schema::create('multas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reserva_id')->nullable()->constrained('reservas')->nullOnDelete();
            $table->string('motivo');
            $table->integer('puntos_descontados')->default(0);
            $table->string('estado', 10)->default('pendiente'); // pendiente | saldada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multas');

        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropColumn(['renovaciones', 'externo_especial']);
        });

        Schema::table('libros', function (Blueprint $table) {
            $table->dropColumn('etiqueta');
        });
    }
};
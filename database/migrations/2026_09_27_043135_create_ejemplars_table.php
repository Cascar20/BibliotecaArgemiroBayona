<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejemplares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('libro_id')->constrained()->cascadeOnDelete();
            $table->string('codigo_inventario')->unique();
            $table->enum('estado', ['disponible', 'reservado', 'prestado', 'dañado'])
                  ->default('disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejemplares');
    }
};
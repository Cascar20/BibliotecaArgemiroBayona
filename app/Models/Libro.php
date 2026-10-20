<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Libro extends Model
{
    use HasFactory;

    protected $fillable = [
    'titulo',
    'autor',
    'isbn',
    'categoria',
    'etiqueta',
    'descripcion',
    'portada',
    ];

    public function ejemplares(): HasMany
    {
        return $this->hasMany(Ejemplar::class);
    }

    // Cuenta cuántos ejemplares están disponibles ahora mismo
    public function ejemplaresDisponibles()
    {
        return $this->ejemplares()->where('estado', 'disponible')->count();
    }

    public function agregarEjemplares(int $cantidad): void
{
    $n = $this->ejemplares()->count();

    for ($i = 0; $i < $cantidad; $i++) {
        do {
            $n++;
            $codigo = sprintf('LIB%04d-%02d', $this->id, $n);
        } while (Ejemplar::where('codigo_inventario', $codigo)->exists());

        $this->ejemplares()->create(['codigo_inventario' => $codigo]);
    }
}

public const ETIQUETAS = [
    'rojo' => [
        'nombre' => 'Rojo',
        'color' => 'danger',
        'regla' => 'Préstamo externo por 8 días, hasta 3 renovaciones',
    ],
    'verde' => [
        'nombre' => 'Verde',
        'color' => 'success',
        'regla' => 'Consulta de 2 horas; préstamo externo especial con autorización',
    ],
    'amarillo' => [
        'nombre' => 'Amarillo',
        'color' => 'warning',
        'regla' => 'Consulta de 2 horas, solo dentro de la biblioteca',
    ],
];

public function infoEtiqueta(): array
{
    return self::ETIQUETAS[$this->etiqueta] ?? self::ETIQUETAS['rojo'];
}

// Fecha límite de devolución según la etiqueta
public function fechaDevolucion(?int $diasEspecial = null): \Illuminate\Support\Carbon
{
    return match ($this->etiqueta) {
        'verde' => $diasEspecial ? now()->addDays($diasEspecial) : now()->addHours(2),
        'amarillo' => now()->addHours(2),
        default => now()->addDays(8),
    };
}
}
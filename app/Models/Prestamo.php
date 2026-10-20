<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
    'user_id',
    'ejemplar_id',
    'reserva_id',
    'fecha_prestamo',
    'fecha_devolucion_esperada',
    'fecha_devolucion_real',
    'renovaciones',
    'externo_especial',
];

protected $casts = [
    'fecha_devolucion_real' => 'datetime',
    'fecha_devolucion_esperada' => 'datetime',
    'externo_especial' => 'boolean',
];

public const MAX_RENOVACIONES = 3;

public function puedeRenovar(): bool
{
    return $this->fecha_devolucion_real === null
        && $this->ejemplar->libro->etiqueta === 'rojo'
        && $this->renovaciones < self::MAX_RENOVACIONES
        && $this->fecha_devolucion_esperada->isFuture();
}

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ejemplar(): BelongsTo
    {
        return $this->belongsTo(Ejemplar::class);
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    public function fueATiempo(): bool
    {
        return $this->fecha_devolucion_real && $this->fecha_devolucion_real->lte($this->fecha_devolucion_esperada);
    }

        
}
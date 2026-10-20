<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'user_id',
        'ejemplar_id',
        'fecha_reserva',
        'fecha_limite',
        'estado',
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_limite' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ejemplar(): BelongsTo
    {
        return $this->belongsTo(Ejemplar::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Canje extends Model
{
    use HasFactory;

    protected $table = 'canjes';

    protected $fillable = [
        'user_id',
        'recompensa_id',
        'puntos_gastados',
        'estado',
        'fecha',
    ];

    protected $casts = [
    'fecha' => 'datetime',
];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recompensa(): BelongsTo
    {
        return $this->belongsTo(Recompensa::class);
    }
}
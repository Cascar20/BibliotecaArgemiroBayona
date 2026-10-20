<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejemplar extends Model
{
    use HasFactory;

    protected $table = 'ejemplares';

    protected $fillable = [
        'libro_id',
        'codigo_inventario',
        'estado',
    ];

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class);
    }
}
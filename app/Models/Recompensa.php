<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    use HasFactory;

    protected $table = 'recompensas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'costo_puntos',
        'stock',
        'imagen',
        'activa',
    ];
    
}
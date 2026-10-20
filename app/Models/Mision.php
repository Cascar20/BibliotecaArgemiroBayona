<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mision extends Model
{
    use HasFactory;

    protected $table = 'misiones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'puntos_otorgados',
        'activa',
    ];
}
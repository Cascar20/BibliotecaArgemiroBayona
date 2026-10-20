<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MisionCompletada extends Model
{
    use HasFactory;

    protected $table = 'mision_completadas';

    protected $fillable = [
        'user_id',
        'mision_id',
        'fecha',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mision(): BelongsTo
    {
        return $this->belongsTo(Mision::class);
    }
}
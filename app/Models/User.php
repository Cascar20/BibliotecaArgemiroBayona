<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'rol',
    'puntos_actuales',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function esSuperadmin(): bool
{
    return $this->rol === 'superadmin';
}

public function esBibliotecario(): bool
{
    return $this->rol === 'bibliotecario';
}

public function esUsuario(): bool
{
    return $this->rol === 'usuario';
}

// Bibliotecario o superadmin
public function puedeGestionarBiblioteca(): bool
{
    return in_array($this->rol, ['bibliotecario', 'superadmin']);
}

public function multas(): HasMany
{
    return $this->hasMany(Multa::class);
}

public function tieneMultaPendiente(): bool
{
    return $this->multas()->where('estado', 'pendiente')->exists();
}
}

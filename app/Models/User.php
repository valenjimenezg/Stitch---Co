<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre', 'apellido', 'email', 'tipo_documento', 'documento_identidad',
        'password', 'telefono', 'rol', 'direcciones', 'lista_deseos'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'direcciones' => 'array',
            'lista_deseos' => 'array',
        ];
    }

    // Accessor para compatibilidad con ->name
    public function getNameAttribute(): string
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellido ?? ''));
    }

    // Relaciones
    public function ordenes()
    {
        return $this->hasMany(Orden::class);
    }

    public function comentarios()
    {
        return $this->hasMany(ComentarioProducto::class, 'user_id');
    }

    // Helpers
    public function carritoActivo()
    {
        return $this->ordenes()->where('estado', 'carrito')->latest()->first();
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isCliente(): bool
    {
        return $this->rol === 'cliente';
    }
}

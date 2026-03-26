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
        'nombre', 'apellido', 'email', 'cedula_identidad',
        'password', 'telefono', 'rol',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function carritos()
    {
        return $this->hasMany(Carrito::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function listaDeseos()
    {
        return $this->hasMany(ListaDeseo::class);
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }

    // Helpers
    public function carritoActivo()
    {
        return $this->carritos()->where('estado', 'activo')->latest()->first();
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

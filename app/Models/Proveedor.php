<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'tipo_documento',
        'documento_identidad',
        'telefono',
        'email',
        'direccion',
        'contacto_alternativo',
        'cuenta_bancaria',
        'redes_sociales',
        'notas',
    ];

    public function productoVariantes()
    {
        return $this->hasMany(ProductoVariante::class);
    }
}

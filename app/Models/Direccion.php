<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direcciones';

    protected $fillable = [
        'user_id', 'nombre_completo', 'direccion',
        'ciudad', 'codigo_postal', 'telefono', 'es_principal',
    ];

    protected function casts(): array
    {
        return ['es_principal' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

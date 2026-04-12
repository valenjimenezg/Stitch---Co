<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Textiles Premium CA',
                'tipo_documento' => 'J',
                'documento_identidad' => '123456789',
                'email' => 'ventas@textilespremium.com',
                'telefono' => '0414-1234567',
                'direccion' => 'Zona Industrial, Galpón 4',
            ],
            [
                'nombre' => 'Mercería Internacional',
                'tipo_documento' => 'J',
                'documento_identidad' => '987654321',
                'email' => 'pedidos@merceriaint.com',
                'telefono' => '0212-9876543',
                'direccion' => 'Centro Comercial Los Telares',
            ],
            [
                'nombre' => 'Hilos del Norte',
                'tipo_documento' => 'J',
                'documento_identidad' => '456789123',
                'email' => 'logistica@hilosdelnorte.com',
                'telefono' => '0412-4455667',
                'direccion' => 'Avenida Principal del Norte, Edificio Sur',
            ]
        ];

        foreach ($proveedores as $prov) {
            Proveedor::firstOrCreate(['email' => $prov['email']], $prov);
        }
    }
}

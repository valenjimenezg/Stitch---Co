<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@stitchco.com.ve'], [
            'nombre'   => 'Admin',
            'apellido' => 'Stitch',
            'password' => Hash::make('admin123'),
            'rol'      => 'admin',
            'telefono' => '+58 412 0000000',
            'tipo_documento' => 'V',
            'documento_identidad' => '11000000',
        ]);

        User::firstOrCreate(['email' => 'cliente@stitchco.com.ve'], [
            'nombre'   => 'María',
            'apellido' => 'González',
            'password' => Hash::make('cliente123'),
            'rol'      => 'cliente',
            'telefono' => '+58 414 1234567',
            'tipo_documento' => 'V',
            'documento_identidad' => '22000000',
        ]);
    }
}

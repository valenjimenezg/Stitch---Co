<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Producto;
use App\Models\ComentarioProducto;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class ComentariosProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        // Ensure we have some fake users to use as reviewers
        $clientIds = [];
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'nombre' => $faker->firstName,
                'apellido' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'telefono' => $faker->phoneNumber,
                'rol' => 'cliente',
                'tipo_documento' => 'V',
                'documento_identidad' => $faker->randomNumber(8, true),
                'email_verified_at' => now(),
            ]);
            $clientIds[] = $user->id;
        }

        // Add some reviews to products
        $productos = Producto::all();
        
        $comentariosPositivos = [
            '¡Excelente calidad! Muy recomendado.',
            'El color es tal cual como se muestra en la foto. Me encantó.',
            'Llegó muy rápido y en perfectas condiciones. Volvería a comprar.',
            'Lo usé para mi último proyecto y quedó espectacular.',
            'Muy buen producto, aunque el precio podría ser un poco más bajo.',
            'La textura es súper suave. Perfecto para lo que necesitaba.',
            'Tienen mucha variedad. Todo excelente.',
            'Me costó conseguirlo pero valió la pena.',
            '100% recomendado.',
            'Ideal para mis trabajos de mercería.',
        ];

        foreach ($productos as $producto) {
            // Give 1 to 5 reviews to each product
            $numReviews = rand(1, 5);
            for ($i = 0; $i < $numReviews; $i++) {
                ComentarioProducto::create([
                    'user_id' => $faker->randomElement($clientIds),
                    'producto_id' => $producto->id,
                    'calificacion' => rand(4, 5), // Mostly positive to help storefront appearance
                    'comentario' => $faker->randomElement($comentariosPositivos),
                    'created_at' => $faker->dateTimeBetween('-3 months', 'now')
                ]);
            }
        }
    }
}

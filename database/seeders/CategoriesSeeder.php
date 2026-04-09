<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        // Limpiar e insertar categorías correctas para mercería
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            'Hilos',
            'Lanas',
            'Telas',
            'Botones',
            'Agujas',
            'Cierres',
            'Cintas',
            'Elásticos',
            'Encajes',
            'Tijeras y Herramientas',
            'Rellenos',
            'Mercería General',
        ];

        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }

        echo "Categorías creadas: " . Category::count() . "\n";
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoVariante;

class FixBotones extends Command
{
    protected $signature = 'fix:botones';
    protected $description = 'Fix botones in BD to actually show buttons';

    public function handle()
    {
        $catBotones = Categoria::where('nombre', 'Botones')->first();

        // 1. Let's find images that look like buttons
        // From previous listings: b.jpg, b2.jpg, b4.webp, blanco.jpg, marfil.jpg, negro.webp
        $buttonImages = [
            'img/productos/1775513107_b.jpg',
            'img/productos/1775565995_b2.jpg',
            'img/productos/1775566182_b4.webp',
            'img/productos/1775094807_blanco.jpg',
            'img/productos/1775094856_marfil.jpg'
        ];

        $titles = [
            'Botones de Nácar Natural',
            'Botón de Madera Vintage',
            'Pack Botones Acrílicos',
            'Botonadura Clásica Blanca',
            'Botones Marfil Premium'
        ];

        // Let's get all products currently in Botones category
        $productos = Producto::where('categoria_id', $catBotones->id)->get();
        if ($productos->count() > 0) {
            foreach ($productos as $idx => $prod) {
                // Update product name
                $prod->nombre = $titles[$idx % count($titles)];
                $prod->save();

                // Update variant image
                foreach ($prod->variantes as $v) {
                    $v->imagen = $buttonImages[$idx % count($buttonImages)];
                    $v->precio = rand(10, 80) / 100; // Buttons are cheap! $0.10 - $0.80
                    $v->save();
                }
            }
            $this->info("¡Botones corregidos!");
        } else {
            $this->info("No hay productos en la categoría botones");
        }
    }
}

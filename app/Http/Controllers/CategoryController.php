<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $slug = strtolower($slug);
        
        // Se mapea directo ya que actualizamos la base de datos a los nombres finales
        $dbCategoryMap = [
            'telas' => 'telas',
            'lanas' => 'lanas',
            'botones' => 'botones',
            'accesorios' => 'accesorios'
        ];

        $searchSlug = $dbCategoryMap[$slug] ?? $slug;
        $searchSlugTitle = \Illuminate\Support\Str::headline($searchSlug);
        
        $variantes = ProductoVariante::with(['producto.categoria', 'producto.variantes'])
            ->whereNull('parent_id')
            ->whereHas('producto.categoria', function($q) use ($searchSlug, $searchSlugTitle) {
                $q->where('nombre', $searchSlug)
                  ->orWhere('nombre', $searchSlugTitle);
            })
            ->paginate(12);

        // Nombre para mostrar en el titulo visual de la vista
        $displayNames = [
            'telas' => 'Telas',
            'lanas' => 'Lanas',
            'botones' => 'Botones',
            'accesorios' => 'Accesorios'
        ];
        $displayName = $displayNames[$slug] ?? \Illuminate\Support\Str::headline($slug);

        return view('categories.show', compact('variantes', 'slug', 'displayName'));
    }
}

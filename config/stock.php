<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de Stock por Categoría — Stitch & Co ERP
    |--------------------------------------------------------------------------
    |
    | Define la unidad base, tipo de empaque estándar, factor de conversión
    | y alertas de stock mínimo por cada categoría de mercería.
    |
    */

    'categorias' => [

        'botones' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'gruesa',
            'units_per_package' => 144,
            'conversion_factor' => 144.00,
            'min_stock_alert'   => 288,
        ],

        'hilos' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'caja',
            'units_per_package' => 12,
            'conversion_factor' => 12.00,
            'min_stock_alert'   => 24,
        ],

        'lanas' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'caja',
            'units_per_package' => 10,
            'conversion_factor' => 10.00,
            'min_stock_alert'   => 20,
        ],

        'telas' => [
            'unit_type'         => 'metro',
            'packaging_type'    => 'rollo',
            'units_per_package' => 50,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 10,
        ],

        'cintas' => [
            'unit_type'         => 'metro',
            'packaging_type'    => 'rollo',
            'units_per_package' => 50,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 15,
        ],

        'elasticos' => [
            'unit_type'         => 'metro',
            'packaging_type'    => 'rollo',
            'units_per_package' => 25,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 10,
        ],

        'agujas' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'paquete',
            'units_per_package' => 10,
            'conversion_factor' => 10.00,
            'min_stock_alert'   => 20,
        ],

        'cremalleras' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'paquete',
            'units_per_package' => 10,
            'conversion_factor' => 10.00,
            'min_stock_alert'   => 15,
        ],

        'tijeras' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'caja',
            'units_per_package' => 6,
            'conversion_factor' => 6.00,
            'min_stock_alert'   => 5,
        ],

        'kits' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'caja',
            'units_per_package' => 1,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 5,
        ],

        'accesorios' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'paquete',
            'units_per_package' => 1,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 10,
        ],

        'costura' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'paquete',
            'units_per_package' => 1,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 10,
        ],

        'tejidos' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'paquete',
            'units_per_package' => 1,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 10,
        ],

        'manualidades' => [
            'unit_type'         => 'unidad',
            'packaging_type'    => 'caja',
            'units_per_package' => 1,
            'conversion_factor' => 1.00,
            'min_stock_alert'   => 5,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Unidades de medida disponibles
    |--------------------------------------------------------------------------
    */
    'unidades' => [
        'Unidad'  => 'Unidad (Botones, Cierres, Agujas...)',
        'Metro'   => 'Metro (Telas, Cintas, Elásticos...)',
        'Rollo'   => 'Rollo (Hilos, Cintas, Elásticos...)',
        'Madeja'  => 'Madeja (Lanas...)',
        'Ovillo'  => 'Ovillo (Lanas...)',
        'Tubino'  => 'Tubino (Hilos...)',
        'Blíster' => 'Blíster (Agujas...)',
        'Caja'    => 'Caja (Botones, Kits...)',
        'Gruesa'  => 'Gruesa (144 unidades — Botones)',
        'Docena'  => 'Docena (12 unidades)',
        'Pieza'   => 'Pieza general',
    ],

];

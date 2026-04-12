<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empaques_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_producto_id')->constrained('detalle_productos')->cascadeOnDelete();
            $table->string('nombre');
            $table->integer('factor_conversion');
            $table->decimal('precio_usd', 10, 2);
            $table->timestamps();
        });

        // Migrate existing variant data into the new table
        $detalles = \Illuminate\Support\Facades\DB::table('detalle_productos')->get();
        foreach($detalles as $detalle) {
            $nombre = $detalle->unidad_nombre ?? $detalle->unidad_medida ?? 'Unidad';
            if (empty($nombre) || $nombre == 'Ninguna') $nombre = 'Unidad';
            
            $factor = $detalle->factor_conversion ?? 1;
            if ($factor < 1) $factor = 1;

            $precio = $detalle->precio_usd ?? 0;
            if ($precio <= 0 && isset($detalle->precio)) {
                 $precio = round($detalle->precio / 40, 2); // basic fallback
            }

            \Illuminate\Support\Facades\DB::table('empaques_producto')->insert([
                'detalle_producto_id' => $detalle->id,
                'nombre' => $nombre,
                'factor_conversion' => $factor,
                'precio_usd' => $precio,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empaques_producto');
    }
};

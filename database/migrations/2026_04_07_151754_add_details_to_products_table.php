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
        Schema::table('products', function (Blueprint $table) {
            $table->string('grosor')->nullable()->after('description');
            $table->string('color')->nullable()->after('grosor');
            $table->string('marca')->nullable()->after('color');
            $table->decimal('cm', 8, 2)->nullable()->after('marca');
            $table->string('unidad_medida')->nullable()->after('cm');
            $table->boolean('en_oferta')->default(0)->after('stock_total_base');
            $table->integer('descuento_porcentaje')->default(0)->after('en_oferta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'grosor', 'color', 'marca', 'cm', 'unidad_medida', 'en_oferta', 'descuento_porcentaje'
            ]);
        });
    }
};

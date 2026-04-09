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
        Schema::table('detalle_productos', function (Blueprint $table) {
            $table->integer('factor_conversion')->default(1)->after('unidad_medida');
            $table->string('unidad_nombre', 100)->nullable()->after('factor_conversion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_productos', function (Blueprint $table) {
            $table->dropColumn(['factor_conversion', 'unidad_nombre']);
        });
    }
};

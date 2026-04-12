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
        if (!Schema::hasColumn('detalle_carritos', 'empaque_id')) {
            Schema::table('detalle_carritos', function (Blueprint $table) {
                $table->foreignId('empaque_id')->nullable()->after('variante_id')->constrained('empaques_producto')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('detalle_ventas', 'empaque_id')) {
            Schema::table('detalle_ventas', function (Blueprint $table) {
                $table->foreignId('empaque_id')->nullable()->after('variante_id')->constrained('empaques_producto')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_carritos', function (Blueprint $table) {
            $table->dropForeign(['empaque_id']);
            $table->dropColumn('empaque_id');
        });

        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropForeign(['empaque_id']);
            $table->dropColumn('empaque_id');
        });
    }
};

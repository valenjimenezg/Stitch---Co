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
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('total_venta');
            $table->decimal('iva_amount', 10, 2)->default(0)->after('subtotal');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('iva_amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('delivery_fee');
            if (!Schema::hasColumn('ventas', 'delivery_method')) {
                $table->string('delivery_method')->nullable()->after('tipo_envio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'iva_amount', 'delivery_fee', 'total_amount']);
            // Drop delivery_method only if we created it here (inferred, dropping is fine)
        });
    }
};

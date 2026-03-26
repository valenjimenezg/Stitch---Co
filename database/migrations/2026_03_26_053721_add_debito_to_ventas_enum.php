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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ventas MODIFY metodo_pago ENUM('efectivo', 'transferencia', 'pago_movil', 'transferencia_p2p', 'debito_inmediato', 'tarjeta', 'paypal') DEFAULT 'efectivo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ventas MODIFY metodo_pago ENUM('efectivo', 'transferencia', 'pago_movil', 'transferencia_p2p', 'tarjeta', 'paypal') DEFAULT 'efectivo'");
    }
};

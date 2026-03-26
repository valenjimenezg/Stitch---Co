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
            $table->string('calle_envio')->nullable()->after('agencia_envio');
            $table->string('ciudad_envio')->nullable()->after('calle_envio');
            $table->string('estado_envio')->nullable()->after('ciudad_envio');
            $table->string('codigo_postal_envio')->nullable()->after('estado_envio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['calle_envio', 'ciudad_envio', 'estado_envio', 'codigo_postal_envio']);
        });
    }
};

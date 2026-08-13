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
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('contacto_alternativo')->nullable();
            $table->text('cuenta_bancaria')->nullable();
            $table->string('redes_sociales')->nullable();
            $table->text('notas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['contacto_alternativo', 'cuenta_bancaria', 'redes_sociales', 'notas']);
        });
    }
};

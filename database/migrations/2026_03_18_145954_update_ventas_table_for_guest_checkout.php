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
            if (\Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
            $table->string('email')->nullable()->after('user_id');
            $table->string('nombre_cliente')->nullable()->after('email');
            $table->string('telefono')->nullable()->after('nombre_cliente');
            $table->string('direccion')->nullable()->after('telefono');
            $table->string('ciudad')->nullable()->after('direccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            if (\Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
            $table->dropColumn(['email', 'nombre_cliente', 'telefono', 'direccion', 'ciudad']);
        });
    }
};

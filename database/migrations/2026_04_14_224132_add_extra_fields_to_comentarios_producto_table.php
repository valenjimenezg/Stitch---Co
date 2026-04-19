<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comentarios_producto', function (Blueprint $table) {
            // Título corto de la reseña (ej: "¡Excelente producto!")
            $table->string('titulo', 120)->nullable()->after('producto_id');

            // Moderación: solo se muestran los aprobados por el admin
            $table->boolean('aprobado')->default(false)->after('comentario');

            // Compra verificada: el usuario realmente compró este producto
            $table->boolean('verified_purchase')->default(false)->after('aprobado');

            // Respuesta del admin/tienda (opcional)
            $table->text('respuesta_admin')->nullable()->after('verified_purchase');

            // Cuándo respondió el admin
            $table->timestamp('respondido_at')->nullable()->after('respuesta_admin');
        });
    }

    public function down(): void
    {
        Schema::table('comentarios_producto', function (Blueprint $table) {
            $table->dropColumn([
                'titulo',
                'aprobado',
                'verified_purchase',
                'respuesta_admin',
                'respondido_at',
            ]);
        });
    }
};

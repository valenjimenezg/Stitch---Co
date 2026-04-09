<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('document_type', ['V', 'E', 'J', 'G'])->after('email')->default('V');
            $table->string('document_number')->nullable()->after('document_type');
        });

        // Copiar cedula_identidad a document_number
        DB::statement('UPDATE users SET document_number = cedula_identidad WHERE cedula_identidad IS NOT NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->unique('document_number');
            $table->dropUnique(['cedula_identidad']);
            $table->dropColumn('cedula_identidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula_identidad', 20)->unique()->nullable()->after('email');
            
            $table->dropUnique(['document_number']);
            $table->dropColumn(['document_type', 'document_number']);
        });
    }
};

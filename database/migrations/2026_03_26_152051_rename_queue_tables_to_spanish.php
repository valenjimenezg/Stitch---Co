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
        if (Schema::hasTable('jobs')) {
            Schema::rename('jobs', 'trabajos');
        }
        if (Schema::hasTable('job_batches')) {
            Schema::rename('job_batches', 'lotes_trabajos');
        }
        if (Schema::hasTable('failed_jobs')) {
            Schema::rename('failed_jobs', 'trabajos_fallidos');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('trabajos')) {
            Schema::rename('trabajos', 'jobs');
        }
        if (Schema::hasTable('lotes_trabajos')) {
            Schema::rename('lotes_trabajos', 'job_batches');
        }
        if (Schema::hasTable('trabajos_fallidos')) {
            Schema::rename('trabajos_fallidos', 'failed_jobs');
        }
    }
};

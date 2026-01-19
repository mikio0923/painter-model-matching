<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                  ->constrained('painter_jobs')
                  ->cascadeOnDelete();

            $table->foreignId('model_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->text('message')->nullable();

            $table->string('status')->default('applied');
            // applied / accepted / rejected / canceled

            $table->timestamps();

            $table->unique(['job_id', 'model_id']); // 二重応募防止
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};


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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                  ->nullable()
                  ->constrained('painter_jobs')
                  ->nullOnDelete();

            $table->foreignId('reviewer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('reviewed_user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('rating'); // very_good / good / bad
            $table->text('comment')->nullable();

            $table->timestamps();

            // 同じ依頼で同じ人へのレビューは1つまで
            $table->unique(['job_id', 'reviewer_id', 'reviewed_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

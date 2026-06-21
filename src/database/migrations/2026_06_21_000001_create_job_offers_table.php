<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                  ->constrained('painter_jobs')
                  ->cascadeOnDelete();

            // 指名されたモデル（users.id）
            $table->foreignId('model_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // 画家から添えるメッセージ
            $table->text('painter_message')->nullable();

            // モデルからのレスポンス（受諾/辞退時のコメント）
            $table->text('model_response')->nullable();

            // pending / accepted / declined
            $table->string('status')->default('pending');

            // モデルが受諾/辞退した時刻
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();

            // 同じ job × 同じモデルへの再オファー不可
            $table->unique(['job_id', 'model_id']);

            $table->index(['model_id', 'status']);
            $table->index(['job_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // カテゴリ別の受信可否（デフォルトは全部 true）
            $table->boolean('application_emails')->default(true);  // 応募関連
            $table->boolean('message_emails')->default(true);      // メッセージ受信
            $table->boolean('review_emails')->default(true);       // レビュー
            $table->boolean('reminder_emails')->default(true);     // 締切等のリマインダー
            $table->boolean('marketing_emails')->default(true);    // お知らせ・宣伝

            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_preferences');
    }
};

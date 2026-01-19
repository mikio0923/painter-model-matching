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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // タイトル
            $table->text('content')->nullable(); // 内容（詳細ページ用）
            $table->string('type')->default('information'); // information, press_release
            $table->date('published_at'); // 公開日
            $table->boolean('is_published')->default(true); // 公開状態
            $table->timestamps();

            $table->index(['type', 'is_published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};

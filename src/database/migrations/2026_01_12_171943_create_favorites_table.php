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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // お気に入りを登録したユーザー
            $table->morphs('favoritable'); // favoritable_id, favoritable_type (ModelProfile, Job)
            $table->timestamps();

            // 同じユーザーが同じアイテムを重複して登録できないようにする
            $table->unique(['user_id', 'favoritable_id', 'favoritable_type'], 'unique_favorite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};

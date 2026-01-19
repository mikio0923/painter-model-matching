<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('model_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('display_name');
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('gender')->nullable();

            $table->string('prefecture')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('body_type')->nullable();

            $table->json('style_tags')->nullable();      // 清楚 / クール 等
            $table->json('pose_ranges')->nullable();     // 全身 / バストアップ 等

            $table->boolean('online_available')->default(false);

            $table->integer('reward_min')->nullable();
            $table->integer('reward_max')->nullable();

            $table->boolean('is_public')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_profiles');
    }
};

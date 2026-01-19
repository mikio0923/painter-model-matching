<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('painter_jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('painter_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            $table->string('usage_purpose')->nullable(); // 個展 / 練習 等

            $table->integer('reward_amount')->nullable();
            $table->string('reward_unit')->default('per_session'); // per_hour / per_session

            $table->string('location_type')->default('online'); // online / offline
            $table->string('prefecture')->nullable();
            $table->string('city')->nullable();

            $table->date('scheduled_date')->nullable();
            $table->date('apply_deadline')->nullable();

            $table->string('status')->default('open'); // open / closed / done

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};


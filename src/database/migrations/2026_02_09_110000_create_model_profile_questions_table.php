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
        Schema::create('model_profile_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('model_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('asker_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('question');
            $table->text('answer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_profile_questions');
    }
};

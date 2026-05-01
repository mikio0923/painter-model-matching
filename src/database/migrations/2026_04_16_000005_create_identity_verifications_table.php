<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('document_type');
            // drivers_license / my_number / passport / insurance_card
            $table->string('front_image_path');
            $table->string('back_image_path')->nullable();
            $table->string('selfie_image_path')->nullable();
            $table->string('status')->default('pending');
            // pending / reviewing / approved / rejected
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
    }
};

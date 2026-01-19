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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 通知を受け取るユーザー
            $table->string('type'); // application_received, application_accepted, application_rejected, message_received, review_received
            $table->string('title');
            $table->text('body')->nullable();
            $table->unsignedBigInteger('related_id')->nullable(); // 関連するID（job_id, application_id, message_idなど）
            $table->string('related_type')->nullable(); // 関連するモデルのタイプ（Job, JobApplication, Messageなど）
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index(['related_id', 'related_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

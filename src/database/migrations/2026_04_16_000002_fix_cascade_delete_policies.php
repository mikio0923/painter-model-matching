<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 既存の外部キー制約があれば削除（冪等化のため）
     */
    private function dropForeignIfExists(string $table, string $constraintName): void
    {
        $exists = DB::selectOne(
            "SELECT 1 FROM information_schema.table_constraints
             WHERE constraint_schema = DATABASE()
               AND table_name = ?
               AND constraint_name = ?
               AND constraint_type = 'FOREIGN KEY'",
            [$table, $constraintName]
        );

        if ($exists) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraintName}`");
        }
    }

    public function up(): void
    {
        // ── messages: sender_id / receiver_id を nullable + FK(nullOnDelete) に ──
        $this->dropForeignIfExists('messages', 'messages_sender_id_foreign');
        $this->dropForeignIfExists('messages', 'messages_receiver_id_foreign');
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable()->change();
            $table->unsignedBigInteger('receiver_id')->nullable()->change();
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('sender_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('receiver_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── reviews: cascadeOnDelete → nullOnDelete ──
        $this->dropForeignIfExists('reviews', 'reviews_reviewer_id_foreign');
        $this->dropForeignIfExists('reviews', 'reviews_reviewed_user_id_foreign');
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable()->change();
            $table->unsignedBigInteger('reviewed_user_id')->nullable()->change();
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('reviewer_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('reviewed_user_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── job_applications: model_id の cascadeOnDelete → nullOnDelete ──
        $this->dropForeignIfExists('job_applications', 'job_applications_model_id_foreign');
        Schema::table('job_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->nullable()->change();
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreign('model_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── painter_jobs: cascadeOnDelete → nullOnDelete ──
        $this->dropForeignIfExists('painter_jobs', 'painter_jobs_painter_id_foreign');
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('painter_id')->nullable()->change();
        });
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->foreign('painter_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // ── messages ──
        $this->dropForeignIfExists('messages', 'messages_sender_id_foreign');
        $this->dropForeignIfExists('messages', 'messages_receiver_id_foreign');
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable(false)->change();
            $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
        });

        // ── reviews ──
        $this->dropForeignIfExists('reviews', 'reviews_reviewer_id_foreign');
        $this->dropForeignIfExists('reviews', 'reviews_reviewed_user_id_foreign');
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable(false)->change();
            $table->unsignedBigInteger('reviewed_user_id')->nullable(false)->change();
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('reviewer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // ── job_applications ──
        $this->dropForeignIfExists('job_applications', 'job_applications_model_id_foreign');
        Schema::table('job_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->nullable(false)->change();
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreign('model_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // ── painter_jobs ──
        $this->dropForeignIfExists('painter_jobs', 'painter_jobs_painter_id_foreign');
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('painter_id')->nullable(false)->change();
        });
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->foreign('painter_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};

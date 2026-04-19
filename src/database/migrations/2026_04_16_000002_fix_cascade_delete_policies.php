<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── messages: sender_id / receiver_id に FK が未設定 → nullable + FK(nullOnDelete) を追加 ──
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable()->change();
            $table->unsignedBigInteger('receiver_id')->nullable()->change();
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('sender_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('receiver_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── reviews: cascadeOnDelete → nullOnDelete ──
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropForeign(['reviewed_user_id']);
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable()->change();
            $table->unsignedBigInteger('reviewed_user_id')->nullable()->change();
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('reviewer_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('reviewed_user_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── job_applications: model_id の cascadeOnDelete → nullOnDelete ──
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['model_id']);
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->nullable()->change();
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreign('model_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── painter_jobs: cascadeOnDelete → nullOnDelete ──
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->dropForeign(['painter_id']);
        });
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
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable(false)->change();
            $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
        });

        // ── reviews ──
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropForeign(['reviewed_user_id']);
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable(false)->change();
            $table->unsignedBigInteger('reviewed_user_id')->nullable(false)->change();
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('reviewer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // ── job_applications ──
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['model_id']);
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->nullable(false)->change();
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreign('model_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // ── painter_jobs ──
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->dropForeign(['painter_id']);
        });
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('painter_id')->nullable(false)->change();
        });
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->foreign('painter_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};

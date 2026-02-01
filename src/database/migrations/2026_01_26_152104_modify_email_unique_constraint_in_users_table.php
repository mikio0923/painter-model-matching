<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 既存のemailユニーク制約を削除
            $table->dropUnique(['email']);
        });

        // emailとroleの複合ユニークキーを追加
        Schema::table('users', function (Blueprint $table) {
            $table->unique(['email', 'role'], 'users_email_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 複合ユニークキーを削除
            $table->dropUnique('users_email_role_unique');
        });

        // 元のemailユニーク制約を復元
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }
};

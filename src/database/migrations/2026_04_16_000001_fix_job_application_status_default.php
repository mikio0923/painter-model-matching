<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 既存の 'applied' データを 'pending' に更新
        DB::table('job_applications')
            ->where('status', 'applied')
            ->update(['status' => 'pending']);

        // デフォルト値を 'pending' に変更
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        DB::table('job_applications')
            ->where('status', 'pending')
            ->update(['status' => 'applied']);

        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('status')->default('applied')->change();
        });
    }
};

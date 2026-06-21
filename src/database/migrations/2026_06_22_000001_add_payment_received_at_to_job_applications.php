<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // モデル（買い手）が報酬を受け取り、取引完了したと宣言した時刻
            $table->timestamp('payment_received_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('payment_received_at');
        });
    }
};

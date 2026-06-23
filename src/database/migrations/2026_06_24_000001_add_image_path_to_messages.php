<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // 1 メッセージ 1 枚まで（複数枚対応は将来 message_attachments テーブルに分離）
            $table->string('image_path')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};

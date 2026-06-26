<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * モデル自身がセルフ Q&A を作成できるよう、asker_id を nullable に変更する。
 * 既存の画家質問データは保持されるが、UI では公開しない（NULL のみ公開対象）。
 */
return new class extends Migration {
    public function up(): void
    {
        // FK を一旦 drop（MySQL は ALTER COLUMN だけで FK は維持されるが、再作成して nullable + nullOnDelete に統一）
        if (DB::getDriverName() === 'mysql') {
            // information_schema を見て既存 FK を消す
            $exists = DB::selectOne(
                "SELECT 1 FROM information_schema.table_constraints
                 WHERE constraint_schema = DATABASE()
                   AND table_name = 'model_profile_questions'
                   AND constraint_name = 'model_profile_questions_asker_id_foreign'
                   AND constraint_type = 'FOREIGN KEY'"
            );
            if ($exists) {
                DB::statement("ALTER TABLE `model_profile_questions` DROP FOREIGN KEY `model_profile_questions_asker_id_foreign`");
            }
        }

        Schema::table('model_profile_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('asker_id')->nullable()->change();
        });

        Schema::table('model_profile_questions', function (Blueprint $table) {
            $table->foreign('asker_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            $exists = DB::selectOne(
                "SELECT 1 FROM information_schema.table_constraints
                 WHERE constraint_schema = DATABASE()
                   AND table_name = 'model_profile_questions'
                   AND constraint_name = 'model_profile_questions_asker_id_foreign'
                   AND constraint_type = 'FOREIGN KEY'"
            );
            if ($exists) {
                DB::statement("ALTER TABLE `model_profile_questions` DROP FOREIGN KEY `model_profile_questions_asker_id_foreign`");
            }
        }

        Schema::table('model_profile_questions', function (Blueprint $table) {
            // 既存 NULL 行があると変換できないため down 時は NULL を残しつつ FK のみ復帰
            $table->foreign('asker_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * 既存の string rating (very_good/good/bad) を 1〜5 の整数評価に変換する。
     *
     * 旧 → 新 のマッピング
     *   very_good → 5
     *   good      → 4
     *   bad       → 2
     */
    public function up(): void
    {
        // ① 一時カラム rating_int を追加
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_int')->nullable()->after('rating');
        });

        // ② 既存データをマップ
        DB::table('reviews')->where('rating', 'very_good')->update(['rating_int' => 5]);
        DB::table('reviews')->where('rating', 'good')->update(['rating_int' => 4]);
        DB::table('reviews')->where('rating', 'bad')->update(['rating_int' => 2]);
        // 想定外の値は中央値 3 にフォールバック
        DB::table('reviews')->whereNull('rating_int')->update(['rating_int' => 3]);

        // ③ 旧 rating を drop し、rating_int を rating にリネーム
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->renameColumn('rating_int', 'rating');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('rating_str')->nullable()->after('rating');
        });

        DB::table('reviews')->where('rating', '>=', 4)->update(['rating_str' => 'very_good']);
        DB::table('reviews')->where('rating', '=', 3)->update(['rating_str' => 'good']);
        DB::table('reviews')->where('rating', '<=', 2)->update(['rating_str' => 'bad']);
        DB::table('reviews')->whereNull('rating_str')->update(['rating_str' => 'good']);

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->renameColumn('rating_str', 'rating');
        });
    }
};

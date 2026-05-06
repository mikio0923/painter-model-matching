<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('painter_profiles', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('gender');                     // 自己紹介
            $table->text('experience')->nullable()->after('bio');                  // 経歴・受賞歴
            $table->json('activity_regions')->nullable()->after('prefecture');     // 活動エリア（複数）
            $table->json('specialties')->nullable()->after('art_styles');          // 得意モチーフ（人物画/風景 等）
            $table->json('sns_links')->nullable()->after('portfolio_url');         // SNSリンク
            $table->unsignedSmallInteger('years_active')->nullable()->after('experience'); // 活動年数
            $table->boolean('accepts_offers')->default(true)->after('years_active');       // オファー受付中
        });
    }

    public function down(): void
    {
        Schema::table('painter_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'experience',
                'activity_regions',
                'specialties',
                'sns_links',
                'years_active',
                'accepts_offers',
            ]);
        });
    }
};

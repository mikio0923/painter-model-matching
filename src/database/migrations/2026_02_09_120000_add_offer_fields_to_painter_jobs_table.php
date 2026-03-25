<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->string('category')->nullable()->after('usage_purpose');
            $table->string('transportation_fee')->nullable()->after('reward_unit'); // なし / あり / 応相談 など
            $table->string('costume_provided')->nullable()->after('transportation_fee'); // なし / あり / 応相談 など
            $table->string('target')->nullable()->after('costume_provided'); // 女性 / 男性 / 指定なし など
            $table->unsignedInteger('recruitment_number')->nullable()->after('target');

            $table->string('address')->nullable()->after('city');
            $table->text('access')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'transportation_fee',
                'costume_provided',
                'target',
                'recruitment_number',
                'address',
                'access',
            ]);
        });
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // model_profile_images: timestamps 追加（不足していた）
        if (!Schema::hasColumn('model_profile_images', 'created_at')) {
            Schema::table('model_profile_images', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('model_profile_images', 'created_at')) {
            Schema::table('model_profile_images', function (Blueprint $table) {
                $table->dropTimestamps();
            });
        }
    }
};

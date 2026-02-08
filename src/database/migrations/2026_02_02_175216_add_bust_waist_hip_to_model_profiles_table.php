<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('model_profiles', function (Blueprint $table) {
            $table->unsignedSmallInteger('bust')->nullable()->after('height');
            $table->unsignedSmallInteger('waist')->nullable()->after('bust');
            $table->unsignedSmallInteger('hip')->nullable()->after('waist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_profiles', function (Blueprint $table) {
            $table->dropColumn(['bust', 'waist', 'hip']);
        });
    }
};

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
            $table->string('hair_type')->nullable()->after('body_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_profiles', function (Blueprint $table) {
            $table->dropColumn('hair_type');
        });
    }
};

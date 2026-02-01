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
            $table->string('shoe_size', 32)->nullable()->after('height');
            $table->string('clothing_size', 32)->nullable()->after('shoe_size');
            $table->json('model_types')->nullable()->after('clothing_size');
            $table->string('occupation')->nullable()->after('bio');
            $table->string('hobbies')->nullable()->after('occupation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_profiles', function (Blueprint $table) {
            $table->dropColumn(['shoe_size', 'clothing_size', 'model_types', 'occupation', 'hobbies']);
        });
    }
};

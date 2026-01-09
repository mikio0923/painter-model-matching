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
            $table->text('bio')->nullable()->after('hair_type');
            $table->text('experience')->nullable()->after('bio');
            $table->string('portfolio_url')->nullable()->after('experience');
            $table->json('sns_links')->nullable()->after('portfolio_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_profiles', function (Blueprint $table) {
            $table->dropColumn(['bio', 'experience', 'portfolio_url', 'sns_links']);
        });
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number_part1', 4)->nullable()->after('email');
            $table->string('phone_number_part2', 4)->nullable()->after('phone_number_part1');
            $table->string('phone_number_part3', 4)->nullable()->after('phone_number_part2');
            $table->string('postal_code_part1', 3)->nullable()->after('phone_number_part3');
            $table->string('postal_code_part2', 4)->nullable()->after('postal_code_part1');
            $table->string('prefecture')->nullable()->after('postal_code_part2');
            $table->string('city')->nullable()->after('prefecture');
            $table->string('street_number')->nullable()->after('city');
            $table->string('building_name')->nullable()->after('street_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number_part1',
                'phone_number_part2',
                'phone_number_part3',
                'postal_code_part1',
                'postal_code_part2',
                'prefecture',
                'city',
                'street_number',
                'building_name',
            ]);
        });
    }
};

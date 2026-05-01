<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('deletion_requested_at')->nullable()->after('deleted_at');
            $table->timestamp('anonymized_at')->nullable()->after('deletion_requested_at');
            $table->string('deletion_reason')->nullable()->after('anonymized_at');
            $table->text('deletion_feedback')->nullable()->after('deletion_reason');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['deletion_requested_at', 'anonymized_at', 'deletion_reason', 'deletion_feedback']);
        });
    }
};

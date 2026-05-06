<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->timestamp('deadline_reminder_sent_at')->nullable()->after('apply_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('painter_jobs', function (Blueprint $table) {
            $table->dropColumn('deadline_reminder_sent_at');
        });
    }
};

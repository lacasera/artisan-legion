<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devs', function (Blueprint $table) {
            $table->unsignedInteger('last_contribution_count')->nullable()->after('last_refreshed_at');
            $table->dateTime('last_polled_at')->nullable()->after('last_contribution_count');
            $table->dateTime('last_active_at')->nullable()->after('last_polled_at');
        });
    }

    public function down(): void
    {
        Schema::table('devs', function (Blueprint $table) {
            $table->dropColumn(['last_contribution_count', 'last_polled_at', 'last_active_at']);
        });
    }
};

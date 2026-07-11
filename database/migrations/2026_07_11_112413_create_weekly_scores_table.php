<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ulid('public_id')->unique();
            $table->unsignedBigInteger('dev_id');
            $table->string('nation', 3)->nullable()->index();
            $table->string('week', 10)->index();
            $table->unsignedInteger('points')->default(0);
            $table->date('day')->nullable();
            $table->unsignedInteger('day_commits')->default(0);
            $table->unsignedInteger('day_points')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at')->nullable();
            $table->unique(['dev_id', 'week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_scores');
    }
};

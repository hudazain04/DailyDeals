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
<<<<<<<< HEAD:database/migrations/2024_07_03_120413_create_logs_table.php
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
========
        Schema::create('notification_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
>>>>>>>> 3e34acb07187c764af6d4a5ad551fc3d02af92a8:database/migrations/2024_06_22_125107_create_notification_users_table.php
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2024_07_03_120413_create_logs_table.php
        Schema::dropIfExists('logs');
========
        Schema::dropIfExists('notification__users');
>>>>>>>> 3e34acb07187c764af6d4a5ad551fc3d02af92a8:database/migrations/2024_06_22_125107_create_notification_users_table.php
    }
};

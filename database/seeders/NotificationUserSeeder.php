<?php

namespace Database\Seeders;

use App\Models\Notification_user;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification_user::factory()->count(10)->create();

    }
}

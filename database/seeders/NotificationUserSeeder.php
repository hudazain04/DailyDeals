<?php

namespace Database\Seeders;

use App\Models\Notification_User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification_User::factory()->count(10)->create();

    }
}

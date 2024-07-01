<?php

namespace Database\Seeders;

use App\Models\Notified;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotifiedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notified::factory()->count(10)->create();

    }
}

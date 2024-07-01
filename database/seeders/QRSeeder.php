<?php

namespace Database\Seeders;

use App\Models\QR;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QR::factory()->count(10)->create();

    }
}

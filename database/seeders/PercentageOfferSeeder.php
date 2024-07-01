<?php

namespace Database\Seeders;

use App\Models\Percentage_offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PercentageOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Percentage_offer::factory()->count(10)->create();

    }
}

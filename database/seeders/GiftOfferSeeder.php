<?php

namespace Database\Seeders;

use App\Models\Gift_offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GiftOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gift_offer::factory()->count(10)->create();

    }
}

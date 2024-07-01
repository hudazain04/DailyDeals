<?php

namespace Database\Seeders;

use App\Models\Discount_offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount_offer::factory()->count(10)->create();

    }
}

<?php

namespace Database\Seeders;

use App\Models\Discount_Offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount_Offer::factory()->count(10)->create();

    }
}

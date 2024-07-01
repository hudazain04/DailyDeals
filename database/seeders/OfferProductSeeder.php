<?php

namespace Database\Seeders;

use App\Models\Offer_product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer_product::factory()->count(10)->create();

    }
}

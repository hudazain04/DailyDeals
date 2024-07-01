<?php

namespace Database\Seeders;

use App\Models\Offer_Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer_Branch::factory()->count(10)->create();

    }
}

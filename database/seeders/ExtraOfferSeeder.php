<?php

namespace Database\Seeders;

use App\Models\Extra_offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtraOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Extra_offer::factory()->count(10)->create();

    }
}

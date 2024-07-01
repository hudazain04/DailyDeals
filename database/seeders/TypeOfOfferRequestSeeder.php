<?php

namespace Database\Seeders;

use App\Models\Type_of_offer_request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOfOfferRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type_of_offer_request::factory()->count(10)->create();

    }
}

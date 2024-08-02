<?php

namespace Database\Seeders;

use App\Models\Type_Of_Offer_Request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOfOfferRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type_Of_Offer_Request::factory()->count(10)->create();

    }
}

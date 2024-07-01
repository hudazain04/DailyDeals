<?php

namespace Database\Seeders;

use App\Models\Product_info;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product_info::factory()->count(10)->create();

    }
}

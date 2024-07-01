<?php

namespace Database\Seeders;

use App\Models\Branch_product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch_product::factory()->count(10)->create();

    }
}

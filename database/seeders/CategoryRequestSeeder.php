<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Category_request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category_request::factory()->count(10)->create();
    }
}

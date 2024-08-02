<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Category_Request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category_Request::factory()->count(10)->create();
    }
}

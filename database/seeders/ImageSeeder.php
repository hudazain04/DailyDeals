<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product_color_image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Image::factory()->count(10)->create();

    }
}

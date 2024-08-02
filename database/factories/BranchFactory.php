<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pathToImage =public_path('SeederImage/6696770023c44.jpg');//'D:\DailyDeals\public\SeederImage\6696770023c44.jpg';
        $tempImagePath = public_path('TempImage/669670e71bb83.jpg');//'D:\DailyDeals\public\TempImage\669670e71bb83.jpg';

        copy($pathToImage, $tempImagePath);

        $uploadedFile = new UploadedFile(
            $tempImagePath,
            'default-avatar.jpg', // Original filename
            'image/jpeg', // MIME type
            null, // Test (optional, to bypass file size checks)
            true // Ensure that the file is marked as "uploaded"
        );
        return [
           'name' => fake()->company(),
           'location' => fake()->streetAddress(),
           'google_maps' => Str::random(20),
           'visible' => 1,
            'rate' => $this->faker->numberBetween(0,5),
//           'image' => $this->faker->word() . '.jpg',
           'category_id' => $this->faker->numberBetween(1, 10),
           'store_id' => $this->faker->numberBetween(1, 10),
            'image'=>$uploadedFile,
        ];
    }
}

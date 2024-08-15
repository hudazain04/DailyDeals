<?php

namespace Database\Factories;

use App\Types\OfferType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
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

            'image' => $uploadedFile,
            'type' => $this->faker->randomElement([OfferType::Percentage, OfferType::Discount, OfferType::Gift, OfferType::Extra]),
//            'price_before' => $this->faker->numberBetween(10, 100),
//            'price_after' => $this->faker->numberBetween(10, 100),
            'period' => $this->faker->numberBetween(1, 10),
            'active' => 1,



        ];
    }
}

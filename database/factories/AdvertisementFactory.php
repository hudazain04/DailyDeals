<?php

namespace Database\Factories;

use App\Types\RequestType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
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
//            'image' => $this->faker->word() . '.jpg',
           'description' => $this->faker->text(100),
//           'approved' => 1,
           'period' => 1,
           'price' =>  $this->faker->numberBetween(10, 100),
//           'invoice' => $this->faker->text(20),
           'shown' => 1,
           'phone_number' => $this->faker->phoneNumber,
           'user_id' => $this->faker->numberBetween(1, 10),
            'image'=>$uploadedFile,
            'status' =>  $this->faker->randomElement([RequestType::Accepted,RequestType::Rejected,RequestType::Pending]),


        ];
    }
}

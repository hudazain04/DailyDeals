<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        return [
            'image' => $this->faker->word() . '.jpg',
           'description' => $this->faker->text(100),
           'approved' => 1,
           'period' => 1,
           'price' =>  $this->faker->numberBetween(10, 100),
           'invoice' => $this->faker->text(20),
           'shown' => 1,
           'phone_number' => $this->faker->phoneNumber,
           'user_id' => $this->faker->numberBetween(1, 10),


        ];
    }
}

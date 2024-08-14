<?php

namespace Database\Factories;

use App\Types\OfferType;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [

            'image' => $this->faker->word() . '.jpg',
            'type' => $this->faker->randomElement([OfferType::Percentage, OfferType::Discount, OfferType::Gift, OfferType::Extra]),
//            'price_before' => $this->faker->numberBetween(10, 100),
//            'price_after' => $this->faker->numberBetween(10, 100),
            'period' => $this->faker->numberBetween(1, 10),
            'active' => 1,



        ];
    }
}

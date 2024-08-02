<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount_offer>
 */
class Discount_OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'discount' => $this->faker->numberBetween(10, 100),
            'offer_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

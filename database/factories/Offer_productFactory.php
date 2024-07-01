<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer_product>
 */
class Offer_productFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price_after' => $this->faker->numberBetween(10, 100),
            'product_id' => $this->faker->numberBetween(1, 10),
            'offer_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Extra_offer>
 */
class Extra_offerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_count' => $this->faker->numberBetween(1, 100),
            'extra_count' => $this->faker->numberBetween(1, 100),
            'product_id' => $this->faker->numberBetween(1, 10),
            'offer_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

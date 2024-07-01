<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Percentage_offer>
 */
class Percentage_offerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'percentage' => $this->faker->numberBetween(1, 100),
            'offer_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

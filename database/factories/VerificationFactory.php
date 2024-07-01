<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verification>
 */
class VerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commercial_record' => $this->faker->word() . '.jpg',
            'merchant_id' => $this->faker->numberBetween(1, 10),
            'store_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

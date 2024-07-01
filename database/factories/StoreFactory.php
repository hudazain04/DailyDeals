<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => $this->faker->text(50),
            'type' => $this->faker->randomElement(['free vendor', 'verified vendor']),
            'visible' => 1,
            'merchant_id' => $this->faker->numberBetween(1, 10),


        ];
    }
}

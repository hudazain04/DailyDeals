<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => fake()->name(),
            'visible' => 1,
            'priority' =>  $this->faker->numberBetween(10, 100),
            // 'parent_category' => $this->faker->numberBetween(1, 10),

        ];
    }
}

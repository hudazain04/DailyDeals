<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
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
           'location' => fake()->streetAddress(),
           'google_maps' => Str::random(20),
           'visible' => 1,
           'image' => $this->faker->word() . '.jpg',
           'category_id' => $this->faker->numberBetween(1, 10),
           'store_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

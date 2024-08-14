<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Size>
 */
class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'size' => Str::random(10),
            'unit' => Str::random(10),
            'price' => $this->faker->numberBetween(1, 100),
            // 'size' => $this->faker->numberBetween(1, 100),
            //'unit' => $this->faker->word(),

        ];
    }
}

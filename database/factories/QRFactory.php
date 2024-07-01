<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QR>
 */
class QRFactory extends Factory
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
            'rate' => $this->faker->numberBetween(1, 5),
            'branch_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

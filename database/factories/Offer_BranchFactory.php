<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer_Branch>
 */
class Offer_BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => 1,
            'offer_id' => $this->faker->numberBetween(1, 10),
            'branch_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}

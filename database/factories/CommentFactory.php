<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'comment' => $this->faker->text(100),
           'offer_id' => $this->faker->numberBetween(1, 10),
           'customer_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}

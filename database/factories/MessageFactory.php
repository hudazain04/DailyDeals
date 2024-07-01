<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => $this->faker->text(100),
            'time' => $this->faker->dateTime,
            'user_id' => $this->faker->numberBetween(1, 10),
            'conversation_id' => $this->faker->numberBetween(1, 10),


        ];
    }
}

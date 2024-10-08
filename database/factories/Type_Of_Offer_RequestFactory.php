<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type_of_offer_request>
 */
class Type_Of_Offer_RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->text(10),
            'description' => $this->faker->text(50),
            'user_id' => $this->faker->numberBetween(1, 10),


        ];
    }
}

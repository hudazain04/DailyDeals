<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category_request>
 */
class Category_RequestFactory extends Factory
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
            'admin_name' => fake()->name(),
            'status' =>  $this->faker->randomElement(['accepted', 'rejected','pending']),
            'user_id' => $this->faker->numberBetween(1, 10),


        ];
    }
}

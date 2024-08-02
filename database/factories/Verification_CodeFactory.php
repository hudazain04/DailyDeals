<?php

namespace Database\Factories;

use App\Models\Verification_Code;
use App\Types\VerificationCodeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verification_code>
 */
class Verification_CodeFactory extends Factory
{
    protected $model = Verification_Code::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement([VerificationCodeType::register_code,VerificationCodeType::password_code]),
            'code' => $this->faker->numberBetween(11111, 99999),
            'user_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}

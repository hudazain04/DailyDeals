<?php

namespace Database\Factories;

use App\Types\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pathToImage = 'public/668fa4cb5acc9.bmp';


        // Create a new UploadedFile instance from the existing file
//        $uploadedFile = new UploadedFile(
//            $pathToImage,
//            'default-avatar.jpg', // Original filename
//            'image/jpeg', // MIME type
//            null, // Test (optional, to bypass file size checks)
//            true // Ensure that the file is marked as "uploaded"
//        );
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
//            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
//            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
//            'image' => $this->faker->word() . '.jpg',
            'phone_number' => $this->faker->phoneNumber,
//            'role' => $this->faker->randomElement(['Admin', 'Customer', 'Merchant', 'Employee']),
            'blocked' => 0,
            'role'=>UserType::Admin,
            'email'=>'admin@gamil.com',
            'password'=>'123456789',
//            'image'=>$uploadedFile,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

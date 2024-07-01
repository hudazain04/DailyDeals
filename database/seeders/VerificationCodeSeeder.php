<?php

namespace Database\Seeders;

use App\Models\Verification_code;
use Illuminate\Database\Seeder;

class VerificationCodeSeeder extends Seeder
{
    public function run(): void
    {
        Verification_code::factory()->count(10)->create();
    }
}

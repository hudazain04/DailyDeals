<?php

namespace Database\Seeders;

use App\Models\User;
use App\Types\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

        User::factory()->count(10)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Types\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(1)->create(['type'=>UserType::Admin,'email'=>'admin@gamil.com','password'=>'123456789']);
    }
}

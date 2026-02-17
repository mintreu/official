<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(6)->create();

        User::create([
            'name' => 'Client Demo',
            'email' => 'client@mintreu.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'CIC Administrator',
            'email' => 'admin@cic.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Maria Santos',
            'email' => 'maria.staff@cic.local',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Pedro Reyes',
            'email' => 'pedro.staff@cic.local',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $this->call(StudentSeeder::class);
    }
}

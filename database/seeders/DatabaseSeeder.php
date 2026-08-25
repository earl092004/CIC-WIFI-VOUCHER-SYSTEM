<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cic.local'],
            ['name' => 'CIC Administrator', 'password' => 'password', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'maria.staff@cic.local'],
            ['name' => 'Maria Santos', 'password' => 'password', 'role' => 'staff']
        );

        User::updateOrCreate(
            ['email' => 'pedro.staff@cic.local'],
            ['name' => 'Pedro Reyes', 'password' => 'password', 'role' => 'staff']
        );

        $this->call(StudentSeeder::class);
    }
}

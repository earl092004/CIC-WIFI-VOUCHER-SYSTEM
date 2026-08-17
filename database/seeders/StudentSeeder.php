<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'student_id' => '2026-0001',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'course' => 'BS Information Technology',
            'year_level' => '1st Year',
            'status' => 'active',
            'pin_hash' => Hash::make('1234'),
        ]);

        Student::create([
            'student_id' => '2026-0002',
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'course' => 'BS Computer Science',
            'year_level' => '2nd Year',
            'status' => 'active',
            'pin_hash' => Hash::make('5678'),
        ]);

        Student::create([
            'student_id' => '2026-0003',
            'first_name' => 'Pedro',
            'last_name' => 'Cruz',
            'course' => 'BS Information Technology',
            'year_level' => '3rd Year',
            'status' => 'inactive',
            'pin_hash' => Hash::make('2468'),
        ]);
    }
}

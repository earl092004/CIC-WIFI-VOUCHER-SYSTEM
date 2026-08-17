<?php

use App\Models\Student;
use Illuminate\Support\Facades\Hash;

it('rejects an invalid student pin', function () {
    Student::create([
        'student_id' => '2026-9991',
        'first_name' => 'Test',
        'last_name' => 'Student',
        'course' => 'BS Information Technology',
        'year_level' => '1st Year',
        'status' => 'active',
        'pin_hash' => Hash::make('1234'),
    ]);

    $response = $this->from('/kiosk/student')->post('/kiosk/student/verify', [
        'student_id' => '2026-9991',
        'pin' => '9999',
    ]);

    $response->assertRedirect('/kiosk/student')
        ->assertSessionHas('error', 'Invalid student PIN.');
});

it('accepts a valid student pin', function () {
    Student::create([
        'student_id' => '2026-9992',
        'first_name' => 'Another',
        'last_name' => 'Student',
        'course' => 'BS Computer Science',
        'year_level' => '2nd Year',
        'status' => 'active',
        'pin_hash' => Hash::make('4321'),
    ]);

    $response = $this->post('/kiosk/student/verify', [
        'student_id' => '2026-9992',
        'pin' => '4321',
    ]);

    $response->assertOk()
        ->assertViewHas('student', fn (Student $student) => $student->student_id === '2026-9992');
});

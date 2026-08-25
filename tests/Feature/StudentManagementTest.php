<?php

use App\Models\Student;
use App\Models\User;

it('allows administrators to view and add students', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('staff.students.index'))
        ->assertOk();

    $this->actingAs($admin)
        ->post(route('staff.students.store'), [
            'student_id' => 'CIC26-99-0001',
            'first_name' => 'New',
            'last_name' => 'Student',
            'course' => 'BSIS',
            'year_level' => '1st Year',
            'pin' => '1234',
        ])
        ->assertRedirect(route('staff.students.index'));

    expect(Student::where('student_id', 'CIC26-99-0001')->exists())->toBeTrue();
});

it('does not allow staff to manage the student directory', function () {
    $staff = User::factory()->create(['role' => 'staff']);

    $this->actingAs($staff)
        ->get(route('staff.students.index'))
        ->assertForbidden();
});

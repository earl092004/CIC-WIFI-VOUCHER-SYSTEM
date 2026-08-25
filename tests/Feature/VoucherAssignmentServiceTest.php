<?php

use App\Models\Student;
use App\Models\User;
use App\Models\Visitor;
use App\Models\WifiVoucher;
use App\Services\VoucherAssignmentService;

it('prevents a student from receiving a second active voucher', function () {
    $student = Student::create([
        'student_id' => '2026-9001',
        'first_name' => 'Test',
        'last_name' => 'Student',
        'course' => 'BSIT',
        'year_level' => '1st Year',
        'status' => 'active',
        'pin_hash' => bcrypt('1234'),
    ]);

    WifiVoucher::create([
        'student_id' => $student->id,
        'voucher_code' => 'ABC-123-XYZ',
        'omada_voucher_id' => 'existing-1',
        'voucher_type' => 'student',
        'duration_minutes' => 480,
        'status' => 'active',
        'network_name' => 'CIC-Student',
        'issued_at' => now(),
        'expires_at' => now()->addHours(8),
    ]);

    $service = new VoucherAssignmentService();

    $result = $service->issueForStudent($student, 480);

    expect($result)->toBeFalse();
});

it('issues a new voucher when the student has none active', function () {
    $student = Student::create([
        'student_id' => '2026-9002',
        'first_name' => 'Another',
        'last_name' => 'Student',
        'course' => 'BSIT',
        'year_level' => '1st Year',
        'status' => 'active',
        'pin_hash' => bcrypt('4321'),
    ]);

    WifiVoucher::create([
        'voucher_code' => 'LOCAL-9002',
        'voucher_type' => 'student',
        'duration_minutes' => 480,
        'status' => 'active',
        'import_batch' => 'test-batch',
        'imported_at' => now(),
    ]);
    $service = new VoucherAssignmentService();

    $result = $service->issueForStudent($student, 480);

    expect($result)->toBeArray()
        ->and($result['voucher_code'])->not->toBeEmpty()
        ->and($result['student_id'])->toBe($student->id);
});

it('issues a new voucher for a visitor and allows lookup by active voucher', function () {
    $staff = User::factory()->create();

    $visitor = Visitor::create([
        'name' => 'Jane Visitor',
        'purpose' => 'School visit',
        'visiting_department' => 'Main Office',
        'contact_number' => '09999999999',
        'authorized_by' => $staff->id,
        'status' => 'active',
    ]);

    WifiVoucher::create([
        'voucher_code' => 'LOCAL-VISITOR',
        'voucher_type' => 'visitor',
        'duration_minutes' => 240,
        'status' => 'active',
        'network_name' => 'CIC-Visitors',
        'import_batch' => 'test-batch',
        'imported_at' => now(),
    ]);

    $service = new VoucherAssignmentService();

    $result = $service->issueForVisitor($visitor, 240);

    expect($result)->toBeArray()
        ->and($result['visitor_id'])->toBe($visitor->id)
        ->and($service->getActiveVoucherForVisitor($visitor))->not->toBeNull();
});

it('renders a polished student voucher display with the network details', function () {
    $student = Student::create([
        'student_id' => '2026-9011',
        'first_name' => 'Polished',
        'last_name' => 'Student',
        'course' => 'BSIT',
        'year_level' => '1st Year',
        'status' => 'active',
        'pin_hash' => bcrypt('1234'),
    ]);

    WifiVoucher::create([
        'student_id' => $student->id,
        'voucher_code' => 'CIC-2026-ADM-9F',
        'omada_voucher_id' => 'voucher-9011',
        'voucher_type' => 'student',
        'duration_minutes' => 480,
        'status' => 'active',
        'issued_at' => now(),
        'expires_at' => now()->addHours(8),
    ]);

    $response = $this->withSession(['kiosk_student_id' => $student->id])
        ->get('/kiosk/student/' . $student->id . '/voucher');

    $response->assertOk()
        ->assertSee('WiFi Access Ready')
        ->assertSee('Network Name')
        ->assertSee('CIC-Student')
        ->assertSee('Voucher Code');
});

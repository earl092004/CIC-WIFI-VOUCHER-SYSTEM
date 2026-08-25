<?php

use App\Models\Student;
use App\Models\WifiVoucher;
use App\Services\VoucherAssignmentService;
use App\Services\VoucherImportService;
use Illuminate\Validation\ValidationException;

it('imports unique six digit voucher codes and skips duplicates', function () {
    $result = app(VoucherImportService::class)->storeCodes(
        "743075,CIC-Student\n965787,CIC-Student\n743075,CIC-Student"
    );

    expect($result['imported'])->toBe(2)
        ->and($result['skipped'])->toBe(0)
        ->and(WifiVoucher::count())->toBe(2);
});

it('assigns an imported voucher and enforces the daily student limit', function () {
    $student = Student::create([
        'student_id' => '2026-9100',
        'first_name' => 'Imported',
        'last_name' => 'Student',
        'course' => 'BSIT',
        'year_level' => '1st Year',
        'status' => 'active',
        'pin_hash' => bcrypt('1234'),
    ]);

    app(VoucherImportService::class)->storeCodes("743075\n965787");
    $service = app(VoucherAssignmentService::class);

    $firstAssignment = $service->issueForStudent($student, 60);

    WifiVoucher::whereKey($firstAssignment['id'])->update([
        'status' => 'expired',
        'expires_at' => now()->subMinute(),
    ]);

    expect(fn () => $service->issueForStudent($student, 60))
        ->toThrow(ValidationException::class);
});

it('rejects assignment when the local voucher pool is empty', function () {
    $student = Student::create([
        'student_id' => '2026-9101',
        'first_name' => 'Empty',
        'last_name' => 'Pool',
        'course' => 'BSIT',
        'year_level' => '1st Year',
        'status' => 'active',
        'pin_hash' => bcrypt('1234'),
    ]);

    expect(fn () => app(VoucherAssignmentService::class)->issueForStudent($student, 60))
        ->toThrow(ValidationException::class);
});

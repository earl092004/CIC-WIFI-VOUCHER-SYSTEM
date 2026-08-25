<?php

use App\Models\Student;
use App\Models\WifiVoucher;
use App\Services\VoucherAssignmentService;
use App\Services\VoucherImportService;
use Illuminate\Validation\ValidationException;

it('keeps imported student and visitor voucher pools separate', function () {
    $student = Student::create([
        'student_id' => '2026-9200',
        'first_name' => 'Pool',
        'last_name' => 'Student',
        'course' => 'BSIT',
        'year_level' => '1st Year',
        'status' => 'active',
        'pin_hash' => bcrypt('1234'),
    ]);

    app(VoucherImportService::class)->storeCodes('111111', 'visitor');

    expect(fn () => app(VoucherAssignmentService::class)->issueForStudent($student))
        ->toThrow(ValidationException::class);

    expect(WifiVoucher::first()->network_name)->toBe('CIC-Visitors');
});

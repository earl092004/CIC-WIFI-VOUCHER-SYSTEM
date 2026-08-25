<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Visitor;
use App\Models\WifiAccessLog;
use App\Models\WifiVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoucherAssignmentService
{
    public function __construct() {}

    public function issueForStudent(Student $student, int $durationMinutes = 480): bool|array
    {
        $existingVoucher = $this->getActiveVoucherForStudent($student);

        if ($existingVoucher) {
            return false;
        }

        $dailyLimit = (int) config('wifi.student_daily_limit', 1);
        $issuedToday = WifiVoucher::query()
            ->where('student_id', $student->id)
            ->whereDate('issued_at', today())
            ->count();

        if ($issuedToday >= $dailyLimit) {
            throw ValidationException::withMessages([
                'student_id' => "Students may receive up to {$dailyLimit} voucher(s) per day.",
            ]);
        }

        return $this->assignVoucher(
            subject: $student,
            voucherType: 'student',
            durationMinutes: $durationMinutes,
            subjectKey: 'student_id',
        );
    }

    public function issueForVisitor(Visitor $visitor, int $durationMinutes = 480): bool|array
    {
        $existingVoucher = $this->getActiveVoucherForVisitor($visitor);

        if ($existingVoucher) {
            return false;
        }

        return $this->assignVoucher(
            subject: $visitor,
            voucherType: 'visitor',
            durationMinutes: $durationMinutes,
            subjectKey: 'visitor_id',
        );
    }

    public function getActiveVoucherForStudent(Student $student): ?WifiVoucher
    {
        return $student->wifiVouchers()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    public function getActiveVoucherForVisitor(Visitor $visitor): ?WifiVoucher
    {
        return $visitor->wifiVouchers()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * @param  Student|Visitor  $subject
     * @return array<string, mixed>|bool
     */
    protected function assignVoucher($subject, string $voucherType, int $durationMinutes, string $subjectKey): bool|array
    {
        $voucher = DB::transaction(function () use ($subject, $voucherType, $durationMinutes, $subjectKey) {
            $voucher = WifiVoucher::query()
                ->where('status', 'active')
                ->where('voucher_type', $voucherType)
                ->whereNull('student_id')
                ->whereNull('visitor_id')
                ->lockForUpdate()
                ->first();

            if (! $voucher) {
                throw ValidationException::withMessages([
                    'voucher' => 'No unused WiFi vouchers are currently available.',
                ]);
            }

            $voucher->update([
                $subjectKey => $subject->id,
                'voucher_type' => $voucherType,
                'usage_status' => 'on_use',
                'network_name' => config("wifi.networks.{$voucherType}"),
                'issued_by' => auth()->id(),
                'issued_at' => now(),
                'expires_at' => now()->addMinutes($durationMinutes),
                'duration_minutes' => $durationMinutes,
            ]);

            return $voucher->fresh();
        });

        $logData = [
            'voucher_id' => $voucher->id,
            'action' => 'voucher_assigned',
            'ip_address' => request()->ip(),
            'performed_by' => auth()->id(),
            'description' => ucfirst($voucherType).' WiFi voucher assigned from local inventory.',
        ];

        if ($voucherType === 'student') {
            $logData['student_id'] = $subject->id;
        } else {
            $logData['visitor_id'] = $subject->id;
        }

        WifiAccessLog::create($logData);

        return [
            'id' => $voucher->id,
            'voucher_code' => $voucher->voucher_code,
            'status' => $voucher->status,
            'expires_at' => $voucher->expires_at,
            'student_id' => $voucherType === 'student' ? $subject->id : null,
            'visitor_id' => $voucherType === 'visitor' ? $subject->id : null,
        ];
    }
}

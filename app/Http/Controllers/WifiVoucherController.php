<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\WifiAccessLog;
use App\Models\WifiVoucher;
use App\Services\VoucherAssignmentService;
use Illuminate\Http\Request;
use App\Contracts\OmadaServiceInterface;

class WifiVoucherController extends Controller
{
    public function issueStudentVoucher(
        Request $request,
        Student $student,
        VoucherAssignmentService $voucherAssignmentService
    ) {
        $result = $voucherAssignmentService->issueForStudent($student, 480);

        if ($result === false) {
            return redirect()
                ->route('kiosk.student.voucher', $student)
                ->with('info', 'You already have an active WiFi voucher.');
        }

        return redirect()->route(
            'kiosk.student.voucher',
            $student
        );
    }

    public function showStudentVoucher(Student $student)
    {
        $voucher = $student->wifiVouchers()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        return view('kiosk.student-voucher', compact(
            'student',
            'voucher'
        ));
    }
}

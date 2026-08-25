<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\WifiAccessLog;
use App\Models\WifiVoucher;
use App\Services\VoucherAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WifiVoucherController extends Controller
{
    public function issueStudentVoucher(
        Request $request,
        Student $student,
        VoucherAssignmentService $voucherAssignmentService
    ) {
        abort_unless((int) $request->session()->get('kiosk_student_id') === $student->id, 403);

        try {
            $result = $voucherAssignmentService->issueForStudent($student, 480);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('kiosk.student.voucher', $student)
                ->with('error', 'No student WiFi vouchers are available right now. Please contact MIS Staff.');
        }

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
        abort_unless((int) request()->session()->get('kiosk_student_id') === $student->id, 403);

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

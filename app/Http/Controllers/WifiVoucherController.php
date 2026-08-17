<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\WifiAccessLog;
use App\Models\WifiVoucher;
use App\Services\MockOmadaService;
use Illuminate\Http\Request;
use App\Contracts\OmadaServiceInterface;

class WifiVoucherController extends Controller
{
    public function issueStudentVoucher(
        Request $request,
        Student $student,
        OmadaServiceInterface $omada
    ) {
        // Check if the student already has an active voucher.
        $existingVoucher = $student->wifiVouchers()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingVoucher) {
            return redirect()
                ->route('kiosk.student.voucher', $student)
                ->with('info', 'You already have an active WiFi voucher.');
        }

        // Ask our temporary Omada service for a voucher.
        $voucherData = $omada->createVoucher(480);

        // Save the voucher locally.
        $voucher = WifiVoucher::create([
            'student_id' => $student->id,
            'omada_voucher_id' => $voucherData['omada_voucher_id'],
            'voucher_code' => $voucherData['voucher_code'],
            'voucher_type' => 'student',
            'duration_minutes' => $voucherData['duration_minutes'],
            'status' => $voucherData['status'],
            'issued_at' => $voucherData['issued_at'],
            'expires_at' => $voucherData['expires_at'],
        ]);

        // Record the action.
        WifiAccessLog::create([
            'student_id' => $student->id,
            'voucher_id' => $voucher->id,
            'action' => 'voucher_generated',
            'ip_address' => $request->ip(),
            'description' => 'Student WiFi voucher generated.',
        ]);

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

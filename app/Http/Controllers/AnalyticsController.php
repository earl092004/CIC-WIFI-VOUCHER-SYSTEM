<?php

namespace App\Http\Controllers;

use App\Models\WifiVoucher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->date('date') ?? today();
        $assigned = WifiVoucher::whereDate('issued_at', $date);

        return view('staff.analytics.index', [
            'date' => $date,
            'studentAssigned' => (clone $assigned)->where('voucher_type', 'student')->count(),
            'visitorAssigned' => (clone $assigned)->where('voucher_type', 'visitor')->count(),
            'availableStudent' => WifiVoucher::where('voucher_type', 'student')->where('status', 'active')->whereNull('student_id')->whereNull('visitor_id')->count(),
            'availableVisitor' => WifiVoucher::where('voucher_type', 'visitor')->where('status', 'active')->whereNull('student_id')->whereNull('visitor_id')->count(),
            'expired' => WifiVoucher::where('status', 'expired')->whereDate('updated_at', $date)->count(),
        ]);
    }
}

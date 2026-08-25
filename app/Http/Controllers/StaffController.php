<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\WifiVoucher;
use App\Services\VoucherImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function dashboard(): View
    {
        return view('staff.dashboard', [
            'availableVouchers' => WifiVoucher::query()
                ->where('status', 'active')
                ->whereNull('student_id')
                ->whereNull('visitor_id')
                ->count(),
            'availableStudentVouchers' => WifiVoucher::query()
                ->where('status', 'active')
                ->where('voucher_type', 'student')
                ->whereNull('student_id')
                ->whereNull('visitor_id')
                ->count(),
            'availableVisitorVouchers' => WifiVoucher::query()
                ->where('status', 'active')
                ->where('voucher_type', 'visitor')
                ->whereNull('student_id')
                ->whereNull('visitor_id')
                ->count(),
            'assignedToday' => WifiVoucher::query()
                ->whereNotNull('student_id')
                ->whereDate('issued_at', today())
                ->count(),
            'studentRequests' => WifiVoucher::query()
                ->with('student')
                ->whereNotNull('student_id')
                ->latest('issued_at')
                ->limit(100)
                ->get(),
        ]);
    }

    public function importVouchers(Request $request, VoucherImportService $importer): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,pdf', 'max:5120'],
            'voucher_type' => ['required', 'in:student,visitor'],
        ]);

        $result = $importer->import($validated['file'], $validated['voucher_type']);

        return back()->with(
            'success',
            "Imported {$result['imported']} new voucher(s); skipped {$result['skipped']} duplicate(s)."
        );
    }

    public function previewImport(Request $request, VoucherImportService $importer): View
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,pdf', 'max:5120'],
            'voucher_type' => ['required', 'in:student,visitor'],
        ]);

        $codes = $importer->extractCodes($validated['file']);
        $existing = WifiVoucher::whereIn('voucher_code', $codes)->pluck('voucher_code')->all();
        $newCodes = array_values(array_diff($codes, $existing));

        session([
            'voucher_import' => [
                'codes' => $newCodes,
                'voucher_type' => $validated['voucher_type'],
            ],
        ]);

        return view('staff.vouchers.import-preview', compact('codes', 'existing'));
    }

    public function confirmImport(Request $request, VoucherImportService $importer): RedirectResponse
    {
        $preview = session('voucher_import');

        abort_unless(is_array($preview) && ! empty($preview['codes']), 422, 'Import preview expired.');

        $result = $importer->storeCodes(
            implode("\n", $preview['codes']),
            $preview['voucher_type']
        );
        $request->session()->forget('voucher_import');

        return redirect()->route('staff.vouchers.index')->with(
            'success',
            "Imported {$result['imported']} voucher(s); skipped {$result['skipped']} duplicate(s)."
        );
    }

    public function vouchers(Request $request): View
    {
        $query = WifiVoucher::query()->with(['student', 'visitor']);

        if ($request->filled('status')) {
            $query->where('usage_status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($voucherQuery) use ($search) {
                $voucherQuery
                    ->where('voucher_code', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($studentQuery) => $studentQuery
                        ->where('student_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        return view('staff.vouchers.index', [
            'vouchers' => $query->latest('imported_at')->latest()->paginate(50)->withQueryString(),
            'counts' => [
                'available' => WifiVoucher::where('usage_status', 'available')->count(),
                'on_use' => WifiVoucher::where('usage_status', 'on_use')->count(),
                'used' => WifiVoucher::where('usage_status', 'used')->count(),
            ],
        ]);
    }

    public function updateVoucherStatus(Request $request, WifiVoucher $voucher): RedirectResponse
    {
        $validated = $request->validate([
            'usage_status' => ['required', Rule::in(['available', 'on_use', 'used'])],
        ]);

        $voucher->update(['usage_status' => $validated['usage_status']]);

        return back()->with('success', "Voucher {$voucher->voucher_code} is now marked as {$voucher->usage_status}.");
    }
}

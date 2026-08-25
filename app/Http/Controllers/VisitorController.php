<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\WifiAccessLog;
use App\Models\WifiVoucher;
use App\Services\VoucherAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitorController extends Controller
{
    public function create(): View
    {
        return view('staff.visitors.create');
    }

    public function store(
        Request $request,
        VoucherAssignmentService $voucherAssignmentService
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'visiting_department' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'duration' => ['required', 'integer', 'in:120,240,480'],
        ]);

        $visitor = Visitor::create([
            'name' => $validated['name'],
            'purpose' => $validated['purpose'],
            'visiting_department' => $validated['visiting_department'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'authorized_by' => auth()->id(),
            'status' => 'active',
        ]);

        $result = $voucherAssignmentService->issueForVisitor($visitor, (int) $validated['duration']);

        if ($result === false) {
            return redirect()->route('staff.visitors.create')
                ->with('info', 'This visitor already has an active WiFi voucher.');
        }

        $voucher = WifiVoucher::query()
            ->where('visitor_id', $visitor->id)
            ->latest()
            ->first();

        if (! $voucher) {
            abort(500, 'Visitor voucher was not created.');
        }

        return redirect()->route(
            'staff.visitors.voucher',
            $voucher
        );
    }

    public function showVoucher(WifiVoucher $voucher): View
    {
        $voucher->load([
            'visitor',
            'issuedBy',
        ]);

        abort_unless(
            $voucher->voucher_type === 'visitor',
            404
        );

        return view('staff.visitors.voucher', compact('voucher'));
    }
}

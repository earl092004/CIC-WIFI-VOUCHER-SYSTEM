<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\WifiAccessLog;
use App\Models\WifiVoucher;
use App\Contracts\OmadaServiceInterface;
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
        OmadaServiceInterface $omada
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'visiting_department' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'duration' => ['required', 'integer', 'in:120,240,480'],
        ]);

        // Create the visitor record and record the staff member
        // who authorized the visitor.
        $visitor = Visitor::create([
            'name' => $validated['name'],
            'purpose' => $validated['purpose'],
            'visiting_department' => $validated['visiting_department'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'authorized_by' => auth()->id(),
            'status' => 'active',
        ]);

        // Ask Omada service to create a voucher.
        $voucherData = $omada->createVoucher(
            (int) $validated['duration']
        );

        // Save the voucher.
        $voucher = WifiVoucher::create([
            'visitor_id' => $visitor->id,
            'omada_voucher_id' => $voucherData['omada_voucher_id'],
            'voucher_code' => $voucherData['voucher_code'],
            'issued_by' => auth()->id(),
            'voucher_type' => 'visitor',
            'duration_minutes' => $voucherData['duration_minutes'],
            'status' => $voucherData['status'],
            'issued_at' => $voucherData['issued_at'],
            'expires_at' => $voucherData['expires_at'],
        ]);

        // Record the action.
        WifiAccessLog::create([
            'visitor_id' => $visitor->id,
            'voucher_id' => $voucher->id,
            'performed_by' => auth()->id(),
            'action' => 'visitor_voucher_generated',
            'ip_address' => $request->ip(),
            'description' => 'Visitor WiFi voucher generated and authorized by staff.',
        ]);

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

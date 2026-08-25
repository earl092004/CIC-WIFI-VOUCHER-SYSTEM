<?php

use App\Models\User;
use App\Models\Visitor;
use App\Models\WifiVoucher;

it('allows staff to view visitor vouchers without management actions', function () {
    $staff = User::factory()->create(['role' => 'staff']);
    $visitor = Visitor::create([
        'name' => 'Jane Visitor',
        'purpose' => 'School meeting',
        'visiting_department' => 'Registrar',
        'contact_number' => '09123456789',
        'authorized_by' => $staff->id,
        'status' => 'active',
    ]);
    $voucher = WifiVoucher::create([
        'visitor_id' => $visitor->id,
        'voucher_code' => 'VIS-123456',
        'voucher_type' => 'visitor',
        'network_name' => 'CIC-Visitors',
        'duration_minutes' => 240,
        'status' => 'active',
        'usage_status' => 'on_use',
        'issued_at' => now(),
        'expires_at' => now()->addHours(4),
    ]);

    WifiVoucher::create([
        'voucher_code' => 'VIS-UNUSED',
        'voucher_type' => 'visitor',
        'network_name' => 'CIC-Visitors',
        'duration_minutes' => 240,
        'status' => 'active',
        'usage_status' => 'available',
    ]);

    $this->actingAs($staff)
        ->get(route('staff.visitors.vouchers'))
        ->assertOk()
        ->assertSee('Jane Visitor')
        ->assertSee('VIS-123456')
        ->assertSee('CIC-Visitors')
        ->assertDontSee('VIS-UNUSED');

    expect($voucher->refresh()->status)->toBe('active')
        ->and($voucher->usage_status)->toBe('on_use');
});

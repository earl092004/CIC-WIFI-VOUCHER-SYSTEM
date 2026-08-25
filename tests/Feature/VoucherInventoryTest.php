<?php

use App\Models\User;
use App\Models\WifiVoucher;

it('allows staff to view and update voucher inventory status', function () {
    $staff = User::factory()->create(['role' => 'admin']);

    $voucher = WifiVoucher::create([
        'voucher_code' => '743075',
        'voucher_type' => 'student',
        'duration_minutes' => 60,
        'status' => 'active',
        'usage_status' => 'available',
        'import_batch' => 'test-batch',
        'imported_at' => now(),
    ]);

    $this->actingAs($staff)
        ->get(route('staff.vouchers.index'))
        ->assertOk()
        ->assertSee('743075')
        ->assertSee('Available');

    $this->actingAs($staff)
        ->patch(route('staff.vouchers.status', $voucher), [
            'usage_status' => 'used',
        ])
        ->assertRedirect();

    expect($voucher->refresh()->usage_status)->toBe('used');
});

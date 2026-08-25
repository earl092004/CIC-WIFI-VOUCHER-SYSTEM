<?php

use App\Models\User;
use App\Models\WifiVoucher;
use App\Services\VoucherExpirationService;

it('expires vouchers whose expiry time has passed', function () {
    $voucher = WifiVoucher::create([
        'voucher_code' => 'EXP-123456',
        'voucher_type' => 'student',
        'network_name' => 'CIC-Student',
        'duration_minutes' => 60,
        'status' => 'active',
        'usage_status' => 'on_use',
        'expires_at' => now()->subMinute(),
    ]);

    expect(app(VoucherExpirationService::class)->expire())->toBe(1)
        ->and($voucher->refresh()->status)->toBe('expired')
        ->and($voucher->usage_status)->toBe('used');
});

it('restricts analytics to administrators', function () {
    $staff = User::factory()->create(['role' => 'staff']);
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($staff)->get(route('staff.analytics'))->assertForbidden();
    $this->actingAs($admin)->get(route('staff.analytics'))->assertOk();
});

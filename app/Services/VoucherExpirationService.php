<?php

namespace App\Services;

use App\Models\WifiVoucher;

class VoucherExpirationService
{
    public function expire(): int
    {
        return WifiVoucher::query()
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => 'expired',
                'usage_status' => 'used',
                'updated_at' => now(),
            ]);
    }
}

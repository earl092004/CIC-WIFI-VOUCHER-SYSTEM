<?php

namespace App\Console\Commands;

use App\Services\VoucherExpirationService;
use Illuminate\Console\Command;

class ExpireWifiVouchers extends Command
{
    protected $signature = 'wifi:vouchers-expire';
    protected $description = 'Mark expired WiFi vouchers as expired';

    public function handle(VoucherExpirationService $expiration): int
    {
        $this->info("Expired {$expiration->expire()} voucher(s).");

        return self::SUCCESS;
    }
}

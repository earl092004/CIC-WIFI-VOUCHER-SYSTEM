<?php

namespace App\Services;

use App\Contracts\OmadaServiceInterface;
use Illuminate\Support\Str;

class MockOmadaService implements OmadaServiceInterface
{
    public function createVoucher(int $durationMinutes = 480, array $metadata = []): array
    {
        $voucherCode = strtoupper(
            Str::random(4).'-'.
            Str::random(4).'-'.
            Str::random(4)
        );

        return [
            'omada_voucher_id' => 'MOCK-'.Str::uuid(),
            'voucher_code' => $voucherCode,
            'duration_minutes' => $durationMinutes,
            'status' => 'active',
            'issued_at' => now(),
            'expires_at' => now()->addMinutes($durationMinutes),
        ];
    }

    public function listVouchers(array $filters = []): array
    {
        return [
            [
                'voucher_code' => 'MOCK-0000-0000',
                'status' => 'unused',
                'duration_minutes' => 480,
            ],
        ];
    }

    public function getVoucher(string $voucherId): ?array
    {
        return null;
    }
}

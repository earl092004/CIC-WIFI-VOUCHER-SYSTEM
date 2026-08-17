<?php

namespace App\Contracts;

interface OmadaServiceInterface
{
    public function createVoucher(int $durationMinutes = 480): array;

    public function getVoucher(string $voucherId): ?array;
}

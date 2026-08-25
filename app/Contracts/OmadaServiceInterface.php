<?php

namespace App\Contracts;

interface OmadaServiceInterface
{
    /**
     * Create a voucher using the Omada controller's supported voucher flow.
     *
     * @return array<string, mixed>
     */
    public function createVoucher(int $durationMinutes = 480, array $metadata = []): array;

    /**
     * Fetch the current voucher list for the active site/group.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listVouchers(array $filters = []): array;

    /**
     * Fetch a single voucher by identifier or code.
     *
     * @return array<string, mixed>|null
     */
    public function getVoucher(string $voucherId): ?array;
}

<?php

namespace App\Services;

use App\Contracts\OmadaServiceInterface;
use Illuminate\Support\Facades\Http;

class RealOmadaService implements OmadaServiceInterface
{
    public function createVoucher(int $durationMinutes = 480): array
    {
        [$baseUrl, $omadaId] = $this->resolveOmadaConnection();
        $token = $this->getAccessToken($baseUrl);

        $response = Http::withToken($token)
            ->acceptJson()
            ->post('/api/v2/sites/' . $omadaId . '/voucher', [
                'duration' => $durationMinutes,
                'count' => 1,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Omada voucher creation failed: ' . $response->body());
        }

        $payload = $response->json();

        return [
            'omada_voucher_id' => $payload['id'] ?? null,
            'voucher_code' => $payload['voucherCode'] ?? $payload['code'] ?? null,
            'duration_minutes' => $durationMinutes,
            'status' => 'active',
            'issued_at' => now(),
            'expires_at' => now()->addMinutes($durationMinutes),
        ];
    }

    public function getVoucher(string $voucherId): ?array
    {
        [$baseUrl, $omadaId] = $this->resolveOmadaConnection();
        $token = $this->getAccessToken($baseUrl);

        $response = Http::withToken($token)
            ->acceptJson()
            ->get('/api/v2/sites/' . $omadaId . '/voucher/' . $voucherId);

        if (! $response->successful()) {
            return null;
        }

        return $response->json();
    }

    /**
     * @return array{0:string,1:string}
     */
    protected function resolveOmadaConnection(): array
    {
        $baseUrl = rtrim(config('services.omada.url', env('OMADA_URL', '')), '/');
        $omadaId = config('services.omada.id', env('OMADA_ID'));

        if (! $baseUrl || ! $omadaId) {
            throw new \RuntimeException('Omada production credentials are not configured.');
        }

        return [$baseUrl, $omadaId];
    }

    protected function getAccessToken(string $baseUrl): string
    {
        $clientId = config('services.omada.client_id', env('OMADA_CLIENT_ID'));
        $clientSecret = config('services.omada.client_secret', env('OMADA_CLIENT_SECRET'));

        if (! $clientId || ! $clientSecret) {
            throw new \RuntimeException('Omada client credentials are not configured.');
        }

        $tokenResponse = Http::baseUrl($baseUrl)
            ->asForm()
            ->post('/api/v2/authorize', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials',
            ]);

        if ($tokenResponse->failed()) {
            throw new \RuntimeException('Omada authentication failed: ' . $tokenResponse->body());
        }

        return $tokenResponse->json('access_token');
    }
}

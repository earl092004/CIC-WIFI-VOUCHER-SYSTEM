<?php

namespace App\Services;

use App\Contracts\OmadaServiceInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RealOmadaService implements OmadaServiceInterface
{
    public function createVoucher(int $durationMinutes = 480, array $metadata = []): array
    {
        [$baseUrl, $siteId] = $this->resolveOmadaConnection();
        $client = $this->buildAuthenticatedClient($baseUrl);

        $response = $client
            ->acceptJson()
            ->post('/api/v2/sites/' . $siteId . '/voucher', array_merge([
                'duration' => $durationMinutes,
                'count' => 1,
            ], $metadata));

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

    public function listVouchers(array $filters = []): array
    {
        [$baseUrl, $siteId] = $this->resolveOmadaConnection();
        $client = $this->buildAuthenticatedClient($baseUrl);

        $response = $client
            ->acceptJson()
            ->get('/api/v2/sites/' . $siteId . '/voucher', $filters);

        if (! $response->successful()) {
            return [];
        }

        $payload = $response->json();

        return is_array($payload) ? $payload : ($payload['data'] ?? []);
    }

    public function getVoucher(string $voucherId): ?array
    {
        [$baseUrl, $siteId] = $this->resolveOmadaConnection();
        $client = $this->buildAuthenticatedClient($baseUrl);

        $response = $client
            ->acceptJson()
            ->get('/api/v2/sites/' . $siteId . '/voucher/' . $voucherId);

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
        $siteId = config('services.omada.site_id', env('OMADA_SITE_ID')) ?? config('services.omada.id', env('OMADA_ID'));

        if (! $baseUrl || ! $siteId) {
            throw new \RuntimeException('Omada production credentials are not configured.');
        }

        return [$baseUrl, $siteId];
    }

    protected function buildAuthenticatedClient(string $baseUrl): PendingRequest
    {
        $username = config('services.omada.username', env('OMADA_USERNAME'));
        $password = config('services.omada.password', env('OMADA_PASSWORD'));
        $terminalUuid = config('services.omada.terminal_uuid', env('OMADA_TERMINAL_UUID')) ?: Str::uuid()->toString();

        if (! $username || ! $password) {
            throw new \RuntimeException('Omada login credentials are not configured.');
        }

        $cookieJar = new \GuzzleHttp\Cookie\CookieJar;

        $loginResponse = Http::withOptions([
            'verify' => false,
            'cookies' => $cookieJar,
        ])
            ->withHeaders([
                'accept' => 'application/json, text/javascript, */*; q=0.01',
                'accept-language' => 'en-US,en;q=0.9',
                'content-type' => 'application/json; charset=UTF-8',
                'request-hash' => '#dashboardGlobal',
                'user-id' => 'defaultId',
                'x-requested-with' => 'XMLHttpRequest',
            ])
            ->post($baseUrl . '/api/v2/login', [
                'username' => $username,
                'password' => $password,
                'terminalUUID' => $terminalUuid,
            ]);

        if ($loginResponse->failed()) {
            throw new \RuntimeException('Omada authentication failed: ' . $loginResponse->body());
        }

        return Http::withOptions([
            'verify' => false,
            'cookies' => $cookieJar,
        ])
            ->baseUrl($baseUrl)
            ->withHeaders([
                'accept' => 'application/json, text/javascript, */*; q=0.01',
                'accept-language' => 'en-US,en;q=0.9',
                'request-hash' => '#dashboardGlobal',
                'user-id' => 'defaultId',
                'x-requested-with' => 'XMLHttpRequest',
            ]);
    }
}

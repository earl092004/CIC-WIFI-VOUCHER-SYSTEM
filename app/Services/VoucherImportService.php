<?php

namespace App\Services;

use App\Models\WifiVoucher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Smalot\PdfParser\Parser;

class VoucherImportService
{
    public function extractCodes(UploadedFile $file): array
    {
        $contents = $file->getClientOriginalExtension() === 'pdf'
            ? (new Parser)->parseFile($file->getRealPath())->getText()
            : file_get_contents($file->getRealPath());

        if ($contents === false) {
            throw ValidationException::withMessages(['file' => 'The voucher file could not be read.']);
        }

        preg_match_all('/(?<!\d)\d{6}(?!\d)/', $contents, $matches);

        return array_values(array_unique($matches[0] ?? []));
    }

    /**
     * Import six-digit voucher codes from CSV, TXT, or pasted PDF text.
     *
     * @return array{batch: string, imported: int, skipped: int, codes: array<int, string>}
     */
    public function import(UploadedFile $file, string $voucherType = 'student'): array
    {
        return $this->storeCodes(implode("\n", $this->extractCodes($file)), $voucherType);
    }

    /**
     * @return array{batch: string, imported: int, skipped: int, codes: array<int, string>}
     */
    public function storeCodes(string $contents, string $voucherType = 'student'): array
    {
        preg_match_all('/(?<!\d)\d{6}(?!\d)/', $contents, $matches);
        $codes = array_values(array_unique($matches[0] ?? []));
        $batch = (string) Str::uuid();
        $imported = 0;

        foreach ($codes as $code) {
            $created = WifiVoucher::query()->firstOrCreate(
                ['voucher_code' => $code],
                [
                    'voucher_type' => $voucherType,
                    'network_name' => config("wifi.networks.{$voucherType}"),
                    'duration_minutes' => config('wifi.voucher_duration_minutes'),
                    'status' => 'active',
                    'usage_status' => 'available',
                    'import_batch' => $batch,
                    'imported_at' => now(),
                ]
            );

            if ($created->wasRecentlyCreated) {
                $imported++;
            }
        }

        return [
            'batch' => $batch,
            'imported' => $imported,
            'skipped' => count($codes) - $imported,
            'codes' => $codes,
        ];
    }
}

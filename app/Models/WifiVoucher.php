<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WifiVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'visitor_id',
        'omada_voucher_id',
        'voucher_code',
        'issued_by',
        'voucher_type',
        'network_name',
        'duration_minutes',
        'status',
        'usage_status',
        'issued_at',
        'expires_at',
        'import_batch',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'expires_at' => 'datetime',
            'imported_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}

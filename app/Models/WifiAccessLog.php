<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WifiAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'visitor_id',
        'voucher_id',
        'performed_by',
        'action',
        'ip_address',
        'device_mac',
        'description',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(WifiVoucher::class, 'voucher_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'course',
        'year_level',
        'status',
        'pin_hash',
        'failed_attempts',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
        'failed_attempts' => 'integer',
    ];

    public function wifiVouchers(): HasMany
    {
        return $this->hasMany(WifiVoucher::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

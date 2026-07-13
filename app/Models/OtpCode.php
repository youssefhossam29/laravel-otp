<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'code_hash',
        'purpose',
        'channel',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at'    => 'datetime',
        ];
    }

    // Get the user who owns this OTP code
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope to get only active codes (not used and not expired)
    public function scopeActive($query)
    {
        return $query->whereNull('used_at')->where('expires_at', '>', now());
    }

    // Scope to filter codes by their purpose (e.g. login, phone_verification)
    public function scopeForPurpose($query, string $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    // Check if the code has expired
    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    // Check if the code has already been used
    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }

    // Check if the code has reached the maximum allowed attempts configured in .env
    public function hasReachedMaxAttempts(): bool
    {
        $maxAttempts = config('otp.max_attempts');
        return $this->attempts >= $maxAttempts;
    }

    // "Burn" the code after it's successfully used to prevent reuse
    public function markAsUsed(): void
    {
        $this->used_at = now();
        $this->save();
    }

    // Increment the number of failed attempts by 1
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }
}

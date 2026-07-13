<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'phone_verified_at',
        'two_factor_enabled',
        'two_factor_preferred_channel',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verified_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
        ];
    }

    // Override the method to send custom email verification notification
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function otpCodes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OtpCode::class);
    }

    public function isTwoFactorEnabled(): bool
    {
        return (bool) $this->two_factor_enabled;
    }

    public function canUseChannel(string $channel): bool
    {
        return match($channel) {
            'email' => !is_null($this->email_verified_at),
            'sms'   => !is_null($this->phone_verified_at),
            default => false,
        };
    }
}

<?php

namespace App\Services\Otp;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\Otp\Channels\SmsOtpChannel;

/**
 * Handles OTP specifically for phone number verification.
 */
class PhoneVerificationService
{
    /**
     * Initialize the phone verification service with the SMS channel.
     */
    public function __construct(private SmsOtpChannel $smsChannel) {}

    /**
     * Generate a new verification code, store it, and send it to the user.
     * It also deletes any previously active phone verification codes for the user.
     */
    public function send(User $user): void
    {
        // Delete any existing active phone verification codes for this user
        $user->otpCodes()->active()->forPurpose('phone_verification')->delete();

        $code = $this->generate();
        
        $this->store($user, $code);

        $this->smsChannel->send($user, $code);
    }

    /**
     * Hash the raw OTP code and store it in the database with an expiration date.
     * Links the code to the user's phone channel.
     */
    private function store(User $user, string $code): void
    {
        $ttlMinutes = config('otp.ttl_minutes', 10);

        $user->otpCodes()->create([
            'code_hash' => Hash::make($code),
            'purpose' => 'phone_verification',
            'channel' => 'sms',
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);
    }

    /**
     * Verify the provided OTP code against the user's active phone verification code.
     * Increments attempts on failure and marks the code as used on success.
     */
    public function verify(User $user, string $code): bool
    {
        $otpCode = $user->otpCodes()
            ->active()
            ->forPurpose('phone_verification')
            ->latest('created_at')
            ->first();

        if (!$otpCode || $otpCode->hasReachedMaxAttempts()) {
            return false;
        }

        if (!Hash::check($code, $otpCode->code_hash)) {
            $otpCode->incrementAttempts();
            return false;
        }

        $otpCode->markAsUsed();
        return true;
    }

    /**
     * Generate a random 6-digit numeric OTP code.
     * Returns the raw code as a string to be passed to the sending methods.
     */
    private function generate(): string
    {
        return (string) random_int(100000, 999999);
    }
}

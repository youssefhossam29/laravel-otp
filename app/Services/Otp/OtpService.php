<?php

namespace App\Services\Otp;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Handles OTP specifically for the 2FA login process.
 */
class OtpService
{
    /**
     * Initialize the OTP service with the channel resolver.
     */
    public function __construct(private OtpChannelResolver $resolver) {}

    /**
     * Generate a new OTP code, store it, and send it to the user.
     * It also deletes any previously active login codes for the user.
     */
    public function send(User $user): void
    {
        // Delete any existing active login codes for this user
        $user->otpCodes()->active()->forPurpose('login')->delete();

        $code = $this->generate();
        
        $this->store($user, $code);

        $this->resolver->resolve($user)->send($user, $code);
    }

    /**
     * Hash the raw OTP code and store it in the database with an expiration date.
     * Links the code to the user's preferred authentication channel.
     */
    private function store(User $user, string $code): void
    {
        $channel = $user->two_factor_preferred_channel ?? 'email';
        $ttlMinutes = config('otp.ttl_minutes', 10);

        $user->otpCodes()->create([
            'code_hash' => Hash::make($code),
            'purpose' => 'login',
            'channel' => $channel,
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);
    }

    /**
     * Verify the provided OTP code against the user's active login code.
     * Increments attempts on failure and marks the code as used on success.
     */
    public function verify(User $user, string $code): bool
    {
        $otpCode = $user->otpCodes()
            ->active()
            ->forPurpose('login')
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

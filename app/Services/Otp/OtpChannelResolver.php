<?php

namespace App\Services\Otp;

use App\Models\User;
use App\Services\Otp\Channels\OtpChannel;
use App\Services\Otp\Channels\EmailOtpChannel;
use InvalidArgumentException;

class OtpChannelResolver
{
    public function __construct(
        private EmailOtpChannel $email,
        private Channels\SmsOtpChannel $sms,
    ) {}

    public function resolve(User $user): OtpChannel
    {
        if (! $user->canUseChannel($user->two_factor_preferred_channel)) {
            throw new \Exception("Cannot send OTP. The selected channel ({$user->two_factor_preferred_channel}) is not verified.");
        }

        return match ($user->two_factor_preferred_channel) {
            'email' => $this->email,
            'sms'   => $this->sms,
            default => throw new \InvalidArgumentException(
                "Unsupported OTP channel: {$user->two_factor_preferred_channel}"
            ),
        };
    }
}

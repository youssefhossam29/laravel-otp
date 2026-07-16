<?php

namespace App\Services\Otp\Channels;

use App\Models\User;
use Twilio\Rest\Client as TwilioClient;

class SmsOtpChannel implements OtpChannel
{
    public function __construct(private TwilioClient $twilio) {}

    public function send(User $user, string $code): void
    {
        $this->twilio->messages->create($user->phone_number, [
            'from' => config('services.twilio.from'),
            'body' => "Your OTP code is: {$code}",
        ]);
    }
}

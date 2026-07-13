<?php

namespace App\Services\Otp\Channels;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class EmailOtpChannel implements OtpChannel
{
    public function send(User $user, string $code): void
    {
        Mail::to($user->email)->send(new OtpMail($code));
    }
}

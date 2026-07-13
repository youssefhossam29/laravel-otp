<?php

namespace App\Services\Otp\Channels;

use App\Models\User;

interface OtpChannel
{
    public function send(User $user, string $code): void;
}

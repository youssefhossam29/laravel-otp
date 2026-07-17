<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Otp\PhoneVerificationService;

class PhoneController extends Controller
{
    public function __construct(
        private PhoneVerificationService $phoneService
    ) {}

    public function update(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
        ]);

        $user = $request->user();

        if ($user->phone_number === $validated['phone']) {
            return back()->with('phone_error', 'Phone number is already up to date.');
        }

        $user->updatePhone($validated['phone']);

        return back()->with('phone_success', 'Phone number updated successfully.');
    }

    public function sendOtp(Request $request)
    {
        $user = $request->user();

        if (!$user->phone_number) {
            return back()->with('phone_error', 'Please add a phone number first.');
        }

        if ($user->phone_verified_at) {
            return back()->with('phone_error', 'Phone number is already verified.');
        }

        try {
            $this->phoneService->send($user);
            return back()->with('status', 'otp-sent')->with('phone_success', 'OTP sent successfully.');
        } catch (\Exception $e) {
            return back()->with('phone_error', 'Failed to send OTP. Please try again.');
        }

    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (!$this->phoneService->verify($user, $validated['code'])) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        $user->update(['phone_verified_at' => now()]);

        return back()->with('phone_success', 'Phone number verified successfully.');
    }
}

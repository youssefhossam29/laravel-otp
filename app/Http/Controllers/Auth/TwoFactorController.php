<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    /**
     * Display the two-factor authentication verification view.
     */
    public function show()
    {
        return view('auth.two_factor.verify');
    }

    /**
     * Verify the provided OTP code and complete the login process if OTP is valid.
     */
    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user  = User::findOrFail($request->session()->get('two_factor_user_id'));

        if (!$this->otpService->verify($user, $validated['code'])) {
            return back()->with('error', 'Invalid or expired code.');
        }

        $request->session()->forget('two_factor_user_id');
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Resend a new OTP code to the user's preferred channel.
     * Redirects back with a success status.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->session()->get('two_factor_user_id'));

        try {
            $this->otpService->send($user);
            return back()->with('success', 'A new OTP code has been sent.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    /**
     * Enable two-factor authentication for the user.
     * Requires a verified channel (email or sms).
     */
    public function enable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'preferred_channel' => 'required|string|in:email',
        ]);

        $user = $request->user();

        if (!$user->canUseChannel($validated['preferred_channel'])) {
            return back()->with('2fa_error', 'This ' . $validated['preferred_channel'] . ' is not verified.');
        }

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_preferred_channel' => $validated['preferred_channel'],
        ]);

        return back()->with('2fa_success', 'Two-factor authentication enabled successfully.');
    }

    /**
     * Update the preferred channel for two-factor authentication.
     */
    public function updateChannel(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'preferred_channel' => 'required|string|in:email',
        ]);

        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return back()->with('2fa_error', 'Two-factor authentication is not enabled.');
        }

        if (!$user->canUseChannel($validated['preferred_channel'])) {
            return back()->with('2fa_error', 'This ' . $validated['preferred_channel'] . ' is not verified.');
        }

        $user->update([
            'two_factor_preferred_channel' => $validated['preferred_channel'],
        ]);

        return back()->with('2fa_success', 'Two-factor channel updated successfully.');
    }

    /**
     * Disable two-factor authentication for the user.
     * Clears the preferred channel setting.
     */
    public function disable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|current_password',
        ]);

        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return back()->with('2fa_error', 'Two-factor authentication is not enabled.');
        }

        $user->update([
            'two_factor_enabled' => false,
            'two_factor_preferred_channel' => null,
        ]);

        return redirect()->back()->with('2fa_success', 'Two-factor authentication disabled.');
    }
}

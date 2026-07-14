<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\Otp\OtpService;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = $request->user();

        if ($user->isTwoFactorEnabled()) {
            Auth::guard('web')->logout();

            $request->session()->put('two_factor_user_id', $user->id);

            try {
                $this->otpService->send($user);
                return redirect()->route('2fa.show')->with('status', 'otp-sent');
            } catch (\Exception $e) {
                $request->session()->forget('two_factor_user_id');
                return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
            }
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

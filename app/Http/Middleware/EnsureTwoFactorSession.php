<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureTwoFactorSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('two_factor_user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first to access this page.');
        }

        $user = User::find($userId);

        if (!$user || !$user->two_factor_enabled) {
            $request->session()->forget('two_factor_user_id');
            return redirect()->route('login')->with('error', 'Two factor authentication is not enabled or user is invalid.');
        }

        return $next($request);
    }
}

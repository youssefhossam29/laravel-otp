<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SecureOTP') }} - Secure Two-Factor Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts & Tailwind CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        <div class="min-h-screen flex flex-col justify-between">
            <div class="w-full max-w-7xl mx-auto px-6">
                <!-- Header -->
                <header class="flex items-center justify-between py-6">
                    <a href="/" class="flex items-center gap-3 no-underline">
                        <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-11 h-11 rounded-xl shadow-md shadow-indigo-600/10" />
                        <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-cyan-500 bg-clip-text text-transparent letter-spacing-tight">
                            {{ config('app.name', 'SecureOTP') }}
                        </span>
                    </a>

                    @if (Route::has('login'))
                        <nav class="flex gap-2">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-slate-600 hover:text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-cyan-500 hover:from-indigo-700 hover:to-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-md shadow-indigo-600/10">Register</a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <!-- Hero Section -->
                <section class="text-center py-20 relative">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(79,70,229,0.04),transparent_60%)] pointer-events-none"></div>

                    <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-600 px-4 py-1.5 rounded-full text-xs font-semibold mb-6">
                        <span class="w-1.5 h-1.5 bg-cyan-500 rounded-full animate-ping"></span>
                        Two-Factor Authentication
                    </div>

                    <h1 class="text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-none mb-6">
                        Protect Your Account<br>with <span class="bg-gradient-to-r from-indigo-600 to-cyan-500 bg-clip-text text-transparent">SecureOTP</span>
                    </h1>

                    <p class="text-lg text-slate-600 max-w-xl mx-auto mb-10 leading-relaxed">
                        Add an extra layer of security to your account with our flexible two-factor authentication system. Choose your preferred channel — Email or SMS.
                    </p>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-cyan-500 hover:from-indigo-700 hover:to-cyan-600 text-white px-8 py-3.5 rounded-xl font-semibold transition active:scale-95 shadow-lg shadow-indigo-600/20">
                            Go to Dashboard
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-cyan-500 hover:from-indigo-700 hover:to-cyan-600 text-white px-8 py-3.5 rounded-xl font-semibold transition active:scale-95 shadow-lg shadow-indigo-600/20">
                            Get Started
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @endauth
                </section>

                <!-- Features Section -->
                <section class="grid grid-cols-1 md:grid-cols-3 gap-8 py-12 mb-16">
                    <div class="bg-white border border-slate-100 rounded-2xl p-8 hover:border-indigo-200 hover:-translate-y-1 transition duration-300 shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-indigo-50 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Email Verification</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">Receive a secure one-time code directly to your verified email address for quick and easy authentication.</p>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-2xl p-8 hover:border-indigo-200 hover:-translate-y-1 transition duration-300 shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-cyan-50 text-cyan-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">SMS Authentication</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">Get your OTP code via SMS to your confirmed phone number using Twilio's reliable messaging infrastructure.</p>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-2xl p-8 hover:border-indigo-200 hover:-translate-y-1 transition duration-300 shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-emerald-50 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Secure & Flexible</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">OTP codes are hashed in the database with a 10-minute expiry. Choose your preferred verification channel anytime.</p>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SecureOTP') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col bg-gray-50">
            <!-- Header Container -->
            <div class="w-full max-w-7xl mx-auto px-6">
                <header class="flex items-center justify-between py-6">
                    <a href="/" class="flex items-center gap-3 text-decoration-none">
                        <x-application-logo class="w-11 h-11 rounded-xl shadow-sm" />
                        <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-cyan-500 bg-clip-text text-transparent">
                            {{ config('app.name', 'SecureOTP') }}
                        </span>
                    </a>

                    @if (Route::has('login'))
                        <nav class="flex gap-2">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-cyan-500 hover:from-indigo-700 hover:to-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-md shadow-indigo-600/10">Register</a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>
            </div>

            <!-- Page Content -->
            <div class="flex-grow flex flex-col justify-center items-center py-12 px-4">
                <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl rounded-2xl border border-gray-100">
                    {{ $slot }}
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </body>
</html>

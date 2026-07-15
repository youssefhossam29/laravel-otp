<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4 shadow-sm border border-indigo-100">
            <svg class="w-8 h-8 text-indigo-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Two-Factor Authentication') }}</h2>
        <p class="mt-2 text-sm text-gray-600 px-4 leading-relaxed">
            {{ __('Please confirm access to your account by entering the authentication code sent to your preferred channel.') }}
        </p>
    </div>

    <!-- Session Status -->
    @if (session('success'))
        <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg flex items-center gap-2 border border-green-200 shadow-sm transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="mb-6 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg border border-red-200 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.verify') }}" class="mt-4">
        @csrf

        <!-- Authentication Code -->
        <div>
            <x-input-label for="code" :value="__('Authentication Code')" class="font-medium text-gray-700" />
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <x-text-input id="code" class="block w-full pl-10 py-3 text-center tracking-[0.75em] font-mono text-xl transition-all border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm rounded-lg" 
                              type="text" name="code" required autofocus autocomplete="one-time-code" maxlength="6" placeholder="••••••" />
            </div>
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div x-data="resendTimer()" x-init="initTimer()" class="flex items-center justify-between mt-8">
            <div>
                <button type="button" 
                        onclick="document.getElementById('resend-otp-form').submit();"
                        x-bind:disabled="!canResend"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:text-indigo-600">
                    <span x-show="canResend">{{ __('Resend Code') }}</span>
                    <span x-show="!canResend" style="display: none;" x-cloak>{{ __('Resend Code in') }} <span x-text="timeLeft"></span>s</span>
                </button>
            </div>

            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-md transition-transform active:scale-95 px-8 py-3 text-sm rounded-lg">
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <form id="resend-otp-form" method="POST" action="{{ route('2fa.resend') }}" class="hidden">
        @csrf
    </form>

    <script>
        function resendTimer() {
            return {
                timeLeft: 60,
                canResend: false,
                initTimer() {
                    let isNewOtpSent = {{ (session('success') || session('status') === 'otp-sent') ? 'true' : 'false' }};
                    let lastSent = localStorage.getItem('otp_last_sent');
                    let now = Math.floor(Date.now() / 1000);

                    if (isNewOtpSent) {
                        this.startNewCountdown();
                    } else if (lastSent && (now - lastSent) < 60) {
                        this.timeLeft = 60 - (now - lastSent);
                        this.runCountdown();
                    } else {
                        // Either timer finished previously, or we refreshed after expiration
                        this.canResend = true;
                    }
                },
                startNewCountdown() {
                    this.timeLeft = 60;
                    localStorage.setItem('otp_last_sent', Math.floor(Date.now() / 1000));
                    this.runCountdown();
                },
                runCountdown() {
                    this.canResend = false;
                    let interval = setInterval(() => {
                        this.timeLeft--;
                        if (this.timeLeft <= 0) {
                            clearInterval(interval);
                            this.canResend = true;
                        }
                    }, 1000);
                }
            }
        }
    </script>
</x-guest-layout>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Phone Number') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add and verify your phone number to use it as a Two-Factor Authentication channel.') }}
        </p>
    </header>

    <div class="mt-6">
        @if (session('phone_success'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg flex items-center gap-2 border border-green-200 shadow-sm transition-all">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('phone_success') }}
            </div>
        @endif
        @if (session('phone_error'))
            <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg border border-red-200 shadow-sm">
                {{ session('phone_error') }}
            </div>
        @endif

        <form method="post" action="{{ route('phone.update') }}" class="space-y-6">
            @csrf
            
            <div class="max-w-xl">
                <x-input-label for="phone" :value="__('Phone Number')" class="font-medium text-gray-700" />
                <div class="relative mt-2">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <x-text-input id="phone" name="phone" type="text" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm rounded-md transition-colors" :value="old('phone', auth()->user()->phone_number)" placeholder="+1234567890" required autofocus autocomplete="tel" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                
                @if (auth()->user()->phone_number && !auth()->user()->phone_verified_at)
                    <div class="mt-2 text-sm text-yellow-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Your phone number is unverified.') }}
                    </div>
                @elseif(auth()->user()->phone_verified_at)
                    <div class="mt-2 text-sm text-green-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ __('Verified.') }}
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save Phone') }}</x-primary-button>
            </div>
        </form>

        @if(auth()->user()->phone_number && !auth()->user()->phone_verified_at)
            <hr class="my-6 border-gray-200">

            <div class="max-w-xl p-6 bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl shadow-sm relative overflow-hidden">
                @if(session('status') !== 'otp-sent' && !$errors->has('code'))
                    <!-- Step 1: Prompt to Send OTP -->
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        {{ __('Phone Verification Required') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('To use your phone number for Two-Factor Authentication, you need to verify it first.') }}
                    </p>
                    
                    <form method="post" action="{{ route('phone.send_otp') }}" class="mt-4">
                        @csrf
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm text-yellow-700 font-medium">
                                {{ __('Your phone number is unverified.') }}
                            </span>
                        </div>
                        <div class="mt-4">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Send OTP Code') }}
                            </x-primary-button>
                        </div>
                    </form>
                @else
                    <!-- Step 2: Verify OTP and Resend Timer -->
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        {{ __('Verify Phone Number') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Please enter the 6-digit verification code sent to your phone.') }}
                    </p>

                    <form method="post" action="{{ route('phone.verify') }}" class="mt-4">
                        @csrf
                        <div>
                            <x-input-label for="code" :value="__('Verification Code')" class="font-medium text-gray-700" />
                            <x-text-input id="code" class="mt-1 block w-full text-center tracking-[0.5em] font-mono transition-colors border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm rounded-md" 
                                          type="text" name="code" required autocomplete="one-time-code" maxlength="6" placeholder="••••••" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div x-data="phoneResendTimer()" x-init="initTimer()" class="flex items-center justify-between mt-6">
                            <div>
                                <button type="button" 
                                        onclick="document.getElementById('send-phone-otp-form').submit();"
                                        x-bind:disabled="!canResend"
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="canResend">{{ __('Resend Code') }}</span>
                                    <span x-show="!canResend" style="display: none;" x-cloak>{{ __('Resend in') }} <span x-text="timeLeft"></span>s</span>
                                </button>
                            </div>
                            <x-primary-button>
                                {{ __('Verify') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <form id="send-phone-otp-form" method="POST" action="{{ route('phone.send_otp') }}" class="hidden">
                        @csrf
                    </form>

                    <script>
                        function phoneResendTimer() {
                            return {
                                timeLeft: 60,
                                canResend: false,
                                initTimer() {
                                    let isNewOtpSent = {{ session('status') === 'otp-sent' ? 'true' : 'false' }};
                                    let lastSent = localStorage.getItem('phone_otp_last_sent');
                                    let now = Math.floor(Date.now() / 1000);

                                    if (isNewOtpSent) {
                                        this.startNewCountdown();
                                    } else if (lastSent && (now - lastSent) < 60) {
                                        this.timeLeft = 60 - (now - lastSent);
                                        this.runCountdown();
                                    } else {
                                        this.canResend = true;
                                    }
                                },
                                startNewCountdown() {
                                    this.timeLeft = 60;
                                    localStorage.setItem('phone_otp_last_sent', Math.floor(Date.now() / 1000));
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
                @endif
            </div>
        @endif
    </div>
</section>

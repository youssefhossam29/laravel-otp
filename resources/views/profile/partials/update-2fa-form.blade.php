<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Two-Factor Authentication (2FA)') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add additional security to your account using two-factor authentication.') }}
        </p>
    </header>

    <div class="mt-6">
        @if (session('2fa_success'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg flex items-center gap-2 border border-green-200 shadow-sm transition-all">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('2fa_success') }}
            </div>
        @endif
        @if (session('2fa_error'))
            <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg border border-red-200 shadow-sm">
                {{ session('2fa_error') }}
            </div>
        @endif

        @if (auth()->user()->isTwoFactorEnabled())
            <div class="p-6 bg-gradient-to-br from-indigo-50 to-blue-100 border border-indigo-200 rounded-xl shadow-md mb-6 relative overflow-hidden transition-all hover:shadow-lg hover:border-indigo-300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-300 rounded-full opacity-30 blur-2xl -mt-10 -mr-10 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-300 rounded-full opacity-20 blur-xl -mb-8 -ml-8 pointer-events-none"></div>
                
                <h3 class="text-lg font-bold text-indigo-800 flex items-center gap-2 relative z-10">
                    <svg class="w-6 h-6 text-indigo-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    {{ __('Two-factor authentication is currently enabled.') }}
                </h3>
                
                <!-- Disable 2FA Button -->
                <div class="mt-6 relative z-10">
                    <x-danger-button class="bg-red-600 hover:bg-red-700 shadow-md transition-transform active:scale-95"
                                     x-data="" 
                                     x-on:click.prevent="$dispatch('open-modal', 'confirm-two-factor-disable')">
                        {{ __('Disable 2FA') }}
                    </x-danger-button>
                </div>

                <hr class="my-6 border-indigo-200 relative z-10 opacity-60">
                
                <!-- Form to update channel -->
                <form method="post" action="{{ route('2fa.update') }}" class="space-y-6 relative z-10">
                    @csrf
                    @method('patch')
                    <div class="max-w-xl">
                        <x-input-label for="preferred_channel" :value="__('Preferred Authentication Channel')" class="font-medium text-indigo-900" />
                        
                        <select id="preferred_channel" name="preferred_channel" class="mt-2 block w-full border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors duration-200 bg-white/80 backdrop-blur-sm">
                            <option value="email" {{ auth()->user()->two_factor_preferred_channel === 'email' ? 'selected' : '' }} {{ !auth()->user()->canUseChannel('email') ? 'disabled' : '' }}>
                                {{ __('Email') }} {{ !auth()->user()->canUseChannel('email') ? '- ' . __('Not Verified') : '' }}
                            </option>
                            <option value="sms" {{ auth()->user()->two_factor_preferred_channel === 'sms' ? 'selected' : '' }} {{ !auth()->user()->canUseChannel('sms') ? 'disabled' : '' }}>
                                {{ __('SMS') }} {{ !auth()->user()->canUseChannel('sms') ? '- ' . __('Not Verified') : '' }}
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('preferred_channel')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button class="bg-indigo-700 hover:bg-indigo-800 shadow-md transition-transform active:scale-95 border-none">
                            {{ __('Save Channel') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Disable 2FA Modal -->
            <x-modal name="confirm-two-factor-disable" :show="$errors->has('password')" focusable>
                <form method="post" action="{{ route('2fa.disable') }}" class="p-6 bg-white rounded-lg">
                    @csrf

                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Disable Two-Factor Authentication') }}
                    </h2>

                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        {{ __('Please enter your password to confirm you would like to disable this security feature.') }}
                    </p>

                    <div class="mt-6">
                        <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-3/4 border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                            placeholder="{{ __('Password') }}"
                            required
                        />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button x-on:click="$dispatch('close')" class="hover:bg-gray-100 transition-colors">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="bg-red-600 hover:bg-red-700 shadow-sm transition-transform active:scale-95">
                            {{ __('Disable 2FA') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>

        @else
            <div class="p-6 bg-gradient-to-br from-gray-50 to-slate-100 border border-gray-200 rounded-xl shadow-sm mb-6 transition-all hover:shadow-md">
                <h3 class="text-md font-semibold text-gray-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    {{ __('Two-factor authentication is not enabled.') }}
                </h3>
                <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                    {{ __('When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication.') }}
                </p>

                <form method="post" action="{{ route('2fa.enable') }}" class="space-y-6">
                    @csrf
                    
                    <div class="max-w-xl">
                        <x-input-label for="preferred_channel" :value="__('Select Channel to Enable')" class="font-medium text-gray-700" />
                        <select id="preferred_channel" name="preferred_channel" class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors duration-200">
                            <option value="email" {{ !auth()->user()->canUseChannel('email') ? 'disabled' : '' }}>
                                {{ __('Email') }} {{ !auth()->user()->canUseChannel('email') ? '- ' . __('Not Verified') : '' }}
                            </option>
                            <option value="sms" {{ !auth()->user()->canUseChannel('sms') ? 'disabled' : '' }}>
                                {{ __('SMS') }} {{ !auth()->user()->canUseChannel('sms') ? '- ' . __('Not Verified') : '' }}
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('preferred_channel')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-md transition-transform active:scale-95">
                            {{ __('Enable 2FA') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</section>

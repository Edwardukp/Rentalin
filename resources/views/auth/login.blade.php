<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold gradient-text">Welcome Back</h2>
        <p class="text-gray-600 mt-2">Sign in to your Rentalin account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="form-label" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="form-label" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            {{-- @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-700 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif --}}
        </div>

        <div class="flex flex-col space-y-4">
            <button type="submit" class="btn-primary w-full py-3 text-base font-semibold">
                {{ __('Sign In') }}
            </button>

            <div class="text-center">
                <span class="text-gray-600">Don't have an account?</span>
                <a class="text-blue-600 hover:text-blue-700 font-medium ml-1" href="{{ route('register') }}">
                    {{ __('Create account') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>

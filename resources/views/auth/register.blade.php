<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold gradient-text">Create Your Account</h2>
        <p class="text-gray-600 mt-2">Join Rentalin to find your perfect kos or rent out your property</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="form-label" />
            <x-text-input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="form-label" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="form-label" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="form-label" />
            <x-text-input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div>
            <x-input-label for="role" :value="__('I am a')" class="form-label" />
            <select id="role" name="role" class="form-input" required>
                <option value="">{{ __('Select your role') }}</option>
                <option value="0" {{ old('role') == '0' ? 'selected' : '' }}>
                    <span class="flex items-center">
                        🏠 {{ __('Tenant - Looking for Kos') }}
                    </span>
                </option>
                <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>
                    <span class="flex items-center">
                        🏢 {{ __('Owner - Renting out Kos') }}
                    </span>
                </option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex flex-col space-y-4">
            <button type="submit" class="btn-primary w-full py-3 text-base font-semibold">
                {{ __('Create Account') }}
            </button>

            <div class="text-center">
                <span class="text-gray-600">Already have an account?</span>
                <a class="text-blue-600 hover:text-blue-700 font-medium ml-1" href="{{ route('login') }}">
                    {{ __('Sign in') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>

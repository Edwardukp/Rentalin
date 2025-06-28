<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        {{-- <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">R</span>
                        </div> --}}
                        <span class="font-semibold text-xl text-gray-900">Rentalin</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-4 py-2 rounded-md transition-colors duration-200">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->isTenant())
                        <x-nav-link :href="route('kos.index')" :active="request()->routeIs('kos.*') && !request()->routeIs('bookings.*')" class="px-4 py-2 rounded-md transition-colors duration-200">
                            {{ __('Browse Kos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')" class="px-4 py-2 rounded-md transition-colors duration-200">
                            {{ __('My Bookings') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->isOwner())
                        <x-nav-link :href="route('owner.kos.index')" :active="request()->routeIs('owner.kos.*')" class="px-4 py-2 rounded-md transition-colors duration-200">
                            {{ __('My Properties') }}
                        </x-nav-link>
                        <x-nav-link :href="route('owner.bookings.index')" :active="request()->routeIs('owner.bookings.*')" class="px-4 py-2 rounded-md transition-colors duration-200">
                            {{ __('Bookings') }}
                        </x-nav-link>
                        <x-nav-link :href="route('owner.analytics')" :active="request()->routeIs('owner.analytics')" class="px-4 py-2 rounded-md transition-colors duration-200">
                            {{ __('Analytics') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4 sm:ms-6">
                <!-- Role Badge -->
                <div class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                    {{ Auth::user()->isOwner() ? 'Owner' : 'Tenant' }}
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <div class="w-6 h-6 bg-gray-600 rounded-full flex items-center justify-center text-white text-xs font-medium mr-2">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->isTenant())
                <x-responsive-nav-link :href="route('kos.index')" :active="request()->routeIs('kos.*') && !request()->routeIs('bookings.*')">
                    {{ __('Browse Kos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                    {{ __('My Bookings') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isOwner())
                <x-responsive-nav-link :href="route('owner.kos.index')" :active="request()->routeIs('owner.kos.*')">
                    {{ __('My Properties') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('owner.bookings.index')" :active="request()->routeIs('owner.bookings.*')">
                    {{ __('Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('owner.analytics')" :active="request()->routeIs('owner.analytics')">
                    {{ __('Analytics') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 py-2">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        {{ Auth::user()->isOwner() ? 'Owner' : 'Tenant' }}
                    </span>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

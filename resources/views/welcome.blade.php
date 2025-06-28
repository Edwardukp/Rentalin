<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Rentalin - Find Your Perfect Kos</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-inter antialiased bg-white min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex items-center space-x-2">
                            {{-- <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">R</span>
                            </div> --}}
                            <span class="font-bold text-xl text-gray-900">Rentalin</span>
                        </div>
                    </div>

                    <!-- Auth Links -->
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="px-4 py-2 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-all duration-200">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-gray-700 hover:text-gray-900 font-medium">
                                    Sign In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="px-4 py-2 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-all duration-200">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>
        <!-- Hero Section -->
        <main class="relative">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                        Find Your Perfect
                        <span class="text-black">Kos</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                        Discover comfortable and affordable kos properties, or list your own property for rent.
                        Rentalin makes finding and managing rental properties simple and secure.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="px-8 py-4 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-xl">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="px-8 py-4 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-xl">
                                Get Started Free
                            </a>
                            <a href="{{ route('login') }}"
                               class="px-8 py-4 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 hover:border-gray-400 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-xl">
                                Sign In
                            </a>
                        @endauth
                    </div>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-700 text-2xl">🏠</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Find Perfect Kos</h3>
                            <p class="text-gray-600">Browse through hundreds of verified kos properties with detailed information and photos.</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-700 text-2xl">💰</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Payments</h3>
                            <p class="text-gray-600">Safe and secure online payments with QRIS integration for hassle-free transactions.</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-700 text-2xl">📱</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Management</h3>
                            <p class="text-gray-600">Manage your bookings, payments, and reviews all in one convenient platform.</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        <div>
                            <div class="text-3xl font-bold text-gray-900">1000+</div>
                            <div class="text-gray-600">Properties</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">5000+</div>
                            <div class="text-gray-600">Happy Tenants</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">500+</div>
                            <div class="text-gray-600">Property Owners</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">4.8★</div>
                            <div class="text-gray-600">Average Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 **text-center**">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 **justify-items-center**">
                    <div class="col-span-1 md:col-span-4"> <div class="flex items-center justify-center space-x-2 mb-4">
                            <span class="font-bold text-xl text-gray-900">Rentalin</span>
                        </div>
                        <p class="text-gray-600 mb-4 max-w-lg mx-auto text-center"> Making kos rental simple, secure, and convenient for everyone.
                            Find your perfect place or list your property with ease.
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-8 pt-8">
                    <p class="text-gray-600">&copy; 2024 Rentalin. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
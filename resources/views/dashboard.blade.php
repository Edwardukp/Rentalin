<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h2>
                <p class="text-gray-600 mt-1">
                    @if(Auth::user()->isOwner())
                        Manage your properties and track your rental business
                    @else
                        Find your perfect kos and manage your bookings
                    @endif
                </p>
            </div>
            <div class="hidden sm:flex items-center space-x-3">
                <div class="px-4 py-2 rounded-full text-sm font-medium {{ Auth::user()->isOwner() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ Auth::user()->isOwner() ? '🏢 Property Owner' : '🏠 Tenant' }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->isOwner())
                <!-- Owner Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Stats Cards -->
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Properties</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->ownedKos()->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 text-xl">🏢</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Active Bookings</p>
                                    <p class="text-2xl font-bold text-gray-900">0</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-green-600 text-xl">📅</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                                    <p class="text-2xl font-bold text-gray-900">Rp 0</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <span class="text-purple-600 text-xl">💰</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Occupancy Rate</p>
                                    <p class="text-2xl font-bold text-gray-900">0%</p>
                                </div>
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <span class="text-orange-600 text-xl">📊</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <a href="{{ route('owner.kos.create') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg hover:from-blue-100 hover:to-purple-100 transition-all duration-200">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-white text-lg">+</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Add New Property</h4>
                                    <p class="text-sm text-gray-600">List a new kos for rent</p>
                                </div>
                            </a>

                            <a href="{{ route('owner.bookings.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg hover:from-green-100 hover:to-blue-100 transition-all duration-200">
                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-white text-lg">📋</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Manage Bookings</h4>
                                    <p class="text-sm text-gray-600">Review and approve bookings</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-gray-400 text-2xl">📊</span>
                                </div>
                                <p class="text-gray-500">No recent activity</p>
                                <p class="text-sm text-gray-400 mt-1">Activity will appear here once you start managing properties</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Tenant Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Stats Cards -->
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Active Bookings</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->bookings()->where('status_pemesanan', 'confirmed')->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 text-xl">🏠</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Pending Bookings</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->bookings()->where('status_pemesanan', 'pending')->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <span class="text-yellow-600 text-xl">⏳</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Reviews Written</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->reviews()->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-green-600 text-xl">⭐</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Find Your Perfect Kos</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <a href="{{ route('kos.index') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg hover:from-blue-100 hover:to-purple-100 transition-all duration-200">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-white text-lg">🔍</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Browse All Kos</h4>
                                    <p class="text-sm text-gray-600">Explore available properties</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Your Bookings</h3>
                        </div>
                        <div class="card-body">
                            @if(Auth::user()->bookings()->count() > 0)
                                <!-- Show recent bookings -->
                                <div class="space-y-3">
                                    @foreach(Auth::user()->bookings()->latest()->take(3)->get() as $booking)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $booking->kos->nama_kos }}</h4>
                                                <p class="text-sm text-gray-600">{{ $booking->tanggal_mulai->format('M d, Y') }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                {{ $booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' :
                                                   ($booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($booking->status_pemesanan) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-gray-400 text-2xl">📅</span>
                                    </div>
                                    <p class="text-gray-500">No bookings yet</p>
                                    <p class="text-sm text-gray-400 mt-1">Start by browsing available kos properties</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    My Bookings 📅
                </h2>
                <p class="text-gray-600 mt-1">Track and manage your kos bookings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($bookings->count() > 0)
                <!-- Bookings List -->
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="card">
                            <div class="card-body">
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                    <!-- Property Image -->
                                    <div class="lg:col-span-1">
                                        <div class="relative h-32 lg:h-24 bg-gray-200 rounded-lg overflow-hidden">
                                            @if($booking->kos->foto && count($booking->kos->foto) > 0)
                                                <img src="{{ asset('storage/' . $booking->kos->foto[0]) }}" 
                                                     alt="{{ $booking->kos->nama_kos }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-black">
                                                    <span class="text-2xl">🏠</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Booking Details -->
                                    <div class="lg:col-span-2">
                                        <div class="flex items-start justify-between mb-2">
                                            <h3 class="font-bold text-lg text-gray-900">{{ $booking->kos->nama_kos }}</h3>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($booking->status_pemesanan) }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-600 text-sm mb-3 flex items-center">
                                            📍 {{ $booking->kos->alamat }}
                                        </p>
                                        
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Check-in:</span>
                                                <p class="font-medium">{{ $booking->tanggal_mulai->format('M d, Y') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Duration:</span>
                                                <p class="font-medium">{{ $booking->durasi }} month{{ $booking->durasi > 1 ? 's' : '' }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Check-out:</span>
                                                <p class="font-medium">{{ $booking->tanggal_mulai->copy()->addMonths((int) $booking->durasi)->format('M d, Y') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Total Price:</span>
                                                <p class="font-bold text-blue-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <span class="text-gray-600 text-sm">Owner:</span>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <div class="w-6 h-6 bg-black rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($booking->kos->owner->name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $booking->kos->owner->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="lg:col-span-1 flex flex-col space-y-2">
                                        <a href="{{ route('bookings.show', $booking) }}" 
                                           class="btn-primary text-center text-sm py-2">
                                            View Details
                                        </a>
                                        
                                        @if($booking->status_pemesanan === 'pending')
                                            <form action="{{ route('bookings.cancel', $booking) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="w-full btn-secondary text-sm py-2 text-red-600 hover:text-red-700 hover:bg-red-50">
                                                    Cancel Booking
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($booking->status_pemesanan === 'confirmed' && !$booking->payment)
                                            <a href="{{ route('payments.create', $booking) }}" 
                                               class="btn-primary text-center text-sm py-2 bg-green-600 hover:bg-green-700">
                                                Pay Now
                                            </a>
                                        @endif
                                        
                                        @if($booking->payment && $booking->payment->status)
                                            <div class="text-center">
                                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                    ✓ Paid
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $bookings->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-gray-400 text-4xl">📅</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No bookings yet</h3>
                        <p class="text-gray-600 mb-6">
                            You haven't made any bookings yet. Start by browsing available kos properties.
                        </p>
                        <a href="{{ route('kos.index') }}" class="btn-primary">
                            Browse Properties
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

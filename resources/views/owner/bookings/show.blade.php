<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Booking Details #{{ $booking->id }}
                </h2>
                <p class="text-gray-600 mt-1">{{ $booking->kos->nama_kos }} • {{ $booking->user->name }}</p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-6">
                <a href="{{ route('owner.bookings.index') }}" class="btn-secondary">
                    ← Back to Bookings
                </a>
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

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Booking Status -->
                    <div class="card">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Booking Status</h3>
                                <span class="px-4 py-2 rounded-full text-sm font-medium 
                                    {{ $booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                       ($booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($booking->status_pemesanan) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($booking->status_pemesanan === 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start space-x-3">
                                        <span class="text-yellow-600 text-xl">⏳</span>
                                        <div>
                                            <h4 class="font-medium text-yellow-800 mb-1">Booking Awaiting Your Approval</h4>
                                            <p class="text-yellow-700 text-sm">
                                                This booking is waiting for your approval. Review the details below and decide whether to confirm or reject this booking.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <form action="{{ route('owner.bookings.updateStatus', $booking) }}" 
                                          method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status_pemesanan" value="confirmed">
                                        <button type="submit" 
                                                class="w-full btn-primary">
                                            ✅ Approve Booking
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('owner.bookings.updateStatus', $booking) }}" 
                                          method="POST" 
                                          class="flex-1"
                                          onsubmit="return confirm('Are you sure you want to reject this booking? This action cannot be undone.')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status_pemesanan" value="canceled">
                                        <button type="submit" 
                                                class="w-full btn-secondary text-red-600 hover:text-red-700 hover:bg-red-50">
                                            ❌ Reject Booking
                                        </button>
                                    </form>
                                </div>
                            @elseif($booking->status_pemesanan === 'confirmed')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-3">
                                        <span class="text-green-600 text-xl">✅</span>
                                        <div>
                                            <h4 class="font-medium text-green-800 mb-1">Booking Confirmed</h4>
                                            <p class="text-green-700 text-sm">
                                                This booking has been confirmed. The tenant can now proceed with payment.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-3">
                                        <span class="text-red-600 text-xl">❌</span>
                                        <div>
                                            <h4 class="font-medium text-red-800 mb-1">Booking Canceled</h4>
                                            <p class="text-red-700 text-sm">
                                                This booking has been canceled and is no longer active.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tenant Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Tenant Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-start space-x-4">
                                <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                    {{ substr($booking->user->name, 0, 1) }}
                                </div>
                                
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-900 mb-2">{{ $booking->user->name }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Email:</span>
                                            <p class="font-medium">{{ $booking->user->email }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Phone:</span>
                                            <p class="font-medium">{{ $booking->user->phone ?? 'Not provided' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Member since:</span>
                                            <p class="font-medium">{{ $booking->user->created_at->format('M Y') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Total bookings:</span>
                                            <p class="font-medium">{{ $booking->user->bookings()->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Property Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-start space-x-4">
                                <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
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
                                
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-900 mb-2">{{ $booking->kos->nama_kos }}</h4>
                                    <p class="text-gray-600 text-sm mb-3 flex items-center">
                                        📍 {{ $booking->kos->alamat }}
                                    </p>
                                    <p class="text-gray-700 text-sm mb-3">{{ Str::limit($booking->kos->fasilitas, 200) }}</p>
                                    
                                    <div class="flex items-center space-x-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Monthly Rate:</span>
                                            <span class="font-bold text-green-600">Rp {{ number_format($booking->kos->harga, 0, ',', '.') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Status:</span>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->kos->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $booking->kos->status ? 'Available' : 'Not Available' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('owner.kos.show', $booking->kos) }}" 
                                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            View Property Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Booking Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Booking Summary</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking ID:</span>
                                <span class="font-medium">#{{ $booking->id }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking Date:</span>
                                <span class="font-medium">{{ $booking->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-in Date:</span>
                                <span class="font-medium">{{ $booking->tanggal_mulai->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-out Date:</span>
                                <span class="font-medium">{{ $booking->end_date->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">{{ $booking->durasi }} month{{ $booking->durasi > 1 ? 's' : '' }}</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Monthly Rate:</span>
                                    <span class="font-medium">Rp {{ number_format($booking->kos->harga, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between mt-2">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium">{{ $booking->durasi }} month{{ $booking->durasi > 1 ? 's' : '' }}</span>
                                </div>
                                
                                <div class="flex justify-between mt-4 pt-4 border-t border-gray-200">
                                    <span class="font-semibold text-gray-900">Total Amount:</span>
                                    <span class="font-bold text-xl text-blue-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Payment Status</h3>
                        </div>
                        <div class="card-body">
                            @if($booking->payment)
                                @if($booking->payment->status)
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-green-600 text-2xl">✓</span>
                                        </div>
                                        <h4 class="font-medium text-green-800 mb-2">Payment Completed</h4>
                                        <p class="text-sm text-green-600 mb-2">
                                            Paid on {{ $booking->payment->tanggal->format('M d, Y') }}
                                        </p>
                                        <div class="text-xs text-gray-500 space-y-1">
                                            <div>Method: {{ ucfirst($booking->payment->metode_pembayaran) }}</div>
                                            <div>Amount: Rp {{ number_format($booking->payment->jumlah, 0, ',', '.') }}</div>
                                            @if($booking->payment->transaction_id)
                                                <div>Transaction ID: {{ $booking->payment->transaction_id }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-yellow-600 text-2xl">⏳</span>
                                        </div>
                                        <h4 class="font-medium text-yellow-800 mb-2">Payment Pending</h4>
                                        <p class="text-sm text-yellow-600 mb-2">
                                            Payment is being processed
                                        </p>
                                        <div class="text-xs text-gray-500">
                                            <div>Method: {{ ucfirst($booking->payment->metode_pembayaran) }}</div>
                                            <div>Amount: Rp {{ number_format($booking->payment->jumlah, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if($booking->status_pemesanan === 'confirmed')
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-blue-600 text-2xl">💳</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">Awaiting Payment</h4>
                                        <p class="text-sm text-gray-600">
                                            Tenant can now proceed with payment
                                        </p>
                                    </div>
                                @elseif($booking->status_pemesanan === 'pending')
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-gray-600 text-2xl">⏸️</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">Payment on Hold</h4>
                                        <p class="text-sm text-gray-600">
                                            Payment will be available after booking approval
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-gray-600 text-2xl">❌</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">No Payment Required</h4>
                                        <p class="text-sm text-gray-600">
                                            Booking has been canceled
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

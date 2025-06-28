<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Booking Details
                </h2>
                <p class="text-gray-600 mt-1">{{ $booking->kos->nama_kos }}</p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-6">
                <a href="{{ route('bookings.index') }}" class="btn-secondary">
                    ← Back to Bookings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
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
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <span class="text-yellow-600 text-xl mr-3">⏳</span>
                                        <div>
                                            <h4 class="font-medium text-yellow-800">Booking Pending</h4>
                                            <p class="text-yellow-700 text-sm mt-1">
                                                Your booking is waiting for confirmation from the property owner. 
                                                You will be notified once it's approved.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status_pemesanan === 'confirmed')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <span class="text-green-600 text-xl mr-3">✅</span>
                                        <div>
                                            <h4 class="font-medium text-green-800">Booking Confirmed</h4>
                                            <p class="text-green-700 text-sm mt-1">
                                                Great! Your booking has been confirmed by the property owner. 
                                                Please proceed with payment to secure your reservation.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <span class="text-red-600 text-xl mr-3">❌</span>
                                        <div>
                                            <h4 class="font-medium text-red-800">Booking Canceled</h4>
                                            <p class="text-red-700 text-sm mt-1">
                                                This booking has been canceled. If you have any questions, 
                                                please contact the property owner.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Property Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-start space-x-4">
                                <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
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
                                    <p class="text-gray-600 text-sm mb-2 flex items-center">
                                        📍 {{ $booking->kos->alamat }}
                                    </p>
                                    <p class="text-gray-700 text-sm">{{ Str::limit($booking->kos->fasilitas, 150) }}</p>
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('kos.show', $booking->kos) }}" 
                                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            View Property Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Property Owner</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($booking->kos->owner->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $booking->kos->owner->name }}</h4>
                                    <p class="text-sm text-gray-600">Property Owner</p>
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
                                <span class="text-gray-600">Check-in Date:</span>
                                <span class="font-medium">{{ $booking->tanggal_mulai->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">{{ $booking->durasi }} month{{ $booking->durasi > 1 ? 's' : '' }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-out Date:</span>
                                <span class="font-medium">{{ $booking->tanggal_mulai->copy()->addMonths((int) $booking->durasi)->format('M d, Y') }}</span>
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
                                @if($booking->payment->isCompleted())
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-green-600 text-2xl">✓</span>
                                        </div>
                                        <h4 class="font-medium text-green-800 mb-2">Payment Completed</h4>
                                        <p class="text-sm text-green-600 mb-2">
                                            Paid on {{ $booking->payment->paid_at->format('M d, Y') }}
                                        </p>
                                        <div class="text-xs text-gray-500 space-y-1">
                                            <div>Method: {{ $booking->payment->metode_pembayaran }}</div>
                                            <div>Amount: Rp {{ number_format($booking->payment->jumlah, 0, ',', '.') }}</div>
                                            <div>Transaction ID: {{ $booking->payment->transaction_id }}</div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('payments.success', $booking->payment) }}" class="btn-secondary w-full text-sm">
                                                View Receipt
                                            </a>
                                        </div>
                                    </div>
                                @elseif($booking->payment->isExpired())
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-red-600 text-2xl">⏰</span>
                                        </div>
                                        <h4 class="font-medium text-red-800 mb-2">Payment Expired</h4>
                                        <p class="text-sm text-red-600 mb-4">
                                            The payment session has expired
                                        </p>
                                        <a href="{{ route('payments.create', $booking) }}" class="btn-primary w-full">
                                            Create New Payment
                                        </a>
                                    </div>
                                @elseif($booking->payment->isFailed())
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-red-600 text-2xl">❌</span>
                                        </div>
                                        <h4 class="font-medium text-red-800 mb-2">Payment Failed</h4>
                                        <p class="text-sm text-red-600 mb-4">
                                            The payment was not successful
                                        </p>
                                        <a href="{{ route('payments.create', $booking) }}" class="btn-primary w-full">
                                            Try Again
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-yellow-600 text-2xl">⏳</span>
                                        </div>
                                        <h4 class="font-medium text-yellow-800 mb-2">Payment Pending</h4>
                                        <p class="text-sm text-yellow-600 mb-4">
                                            Complete your payment to secure the booking
                                        </p>
                                        <div class="space-y-2">
                                            <a href="{{ route('payments.show', $booking->payment) }}" class="btn-primary w-full">
                                                Continue Payment
                                            </a>
                                            <div class="text-xs text-gray-500">
                                                <div>Amount: Rp {{ number_format($booking->payment->jumlah, 0, ',', '.') }}</div>
                                                <div>Expires: {{ $booking->payment->expires_at->format('M d, Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if($booking->status_pemesanan === 'confirmed')
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-blue-600 text-2xl">💳</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">Ready for Payment</h4>
                                        <p class="text-sm text-gray-600 mb-4">
                                            Your booking is confirmed. Complete payment to secure your reservation.
                                        </p>
                                        <a href="{{ route('payments.create', $booking) }}" class="btn-primary w-full">
                                            Pay with QRIS
                                        </a>
                                        <div class="mt-2 text-xs text-gray-500">
                                            Amount: Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-gray-400 text-2xl">💳</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">Payment Not Available</h4>
                                        <p class="text-sm text-gray-600">
                                            Payment will be available once your booking is confirmed.
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($booking->status_pemesanan === 'pending')
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('bookings.cancel', $booking) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full btn-secondary text-red-600 hover:text-red-700 hover:bg-red-50">
                                        Cancel Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

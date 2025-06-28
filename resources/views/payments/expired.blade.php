<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Payment Expired
                </h2>
                <p class="text-gray-600 mt-1">This payment session has expired</p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-6">
                <a href="{{ route('bookings.show', $payment->booking) }}" class="btn-secondary">
                    ← Back to Booking
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Expired Message -->
            <div class="card mb-8">
                <div class="card-body text-center py-12">
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-red-600 text-4xl">⏰</span>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Payment Session Expired</h3>
                    <p class="text-gray-600 mb-6">
                        The payment session for <strong>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</strong> 
                        has expired and is no longer valid.
                    </p>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 inline-block">
                        <div class="text-sm text-red-800 mb-1">Payment Reference</div>
                        <div class="font-mono text-red-900 font-medium">{{ $payment->payment_reference }}</div>
                    </div>
                    
                    <p class="text-sm text-gray-500">
                        Expired on {{ $payment->expires_at->format('M d, Y \a\t H:i') }}
                    </p>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="card mb-8">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Booking Information</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booking ID:</span>
                            <span class="font-medium">#{{ $payment->booking->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Property:</span>
                            <span class="font-medium">{{ $payment->booking->kos->nama_kos }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-in Date:</span>
                            <span class="font-medium">{{ $payment->booking->tanggal_mulai->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-medium">{{ $payment->booking->durasi }} month{{ $payment->booking->durasi > 1 ? 's' : '' }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900">Amount:</span>
                                <span class="font-bold text-lg text-gray-900">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What to do next -->
            <div class="card mb-8">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">What can you do?</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                1
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">Create a New Payment</div>
                                <div class="text-sm text-gray-600">
                                    If your booking is still confirmed, you can create a new payment session.
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                2
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">Contact Property Owner</div>
                                <div class="text-sm text-gray-600">
                                    Reach out to {{ $payment->booking->kos->owner->name }} if you need assistance.
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                3
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">Check Booking Status</div>
                                <div class="text-sm text-gray-600">
                                    Make sure your booking is still confirmed before creating a new payment.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if($payment->booking->isConfirmed())
                    <a href="{{ route('payments.create', $payment->booking) }}" class="btn-primary">
                        Create New Payment
                    </a>
                @endif
                <a href="{{ route('bookings.show', $payment->booking) }}" class="btn-secondary">
                    View Booking Details
                </a>
                <a href="{{ route('bookings.index') }}" class="btn-secondary">
                    View All Bookings
                </a>
            </div>

            <!-- Important Notice -->
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <span class="text-yellow-600 text-xl">⚠️</span>
                    <div>
                        <h4 class="font-medium text-yellow-800 mb-1">Important Notice</h4>
                        <p class="text-sm text-yellow-700">
                            Payment sessions expire after 30 minutes for security reasons. 
                            If you need more time, you can always create a new payment session.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

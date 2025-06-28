<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Payment Successful
                </h2>
                <p class="text-gray-600 mt-1">Your payment has been completed successfully</p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-6">
                <a href="{{ route('bookings.index') }}" class="btn-secondary">
                    View All Bookings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="card mb-8">
                <div class="card-body text-center py-12">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-green-600 text-4xl">✓</span>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Payment Completed!</h3>
                    <p class="text-gray-600 mb-6">
                        Your payment of <strong>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</strong> 
                        has been successfully processed.
                    </p>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 inline-block">
                        <div class="text-sm text-green-800 mb-1">Transaction ID</div>
                        <div class="font-mono text-green-900 font-medium">{{ $payment->transaction_id }}</div>
                    </div>
                    
                    <p class="text-sm text-gray-500">
                        Payment completed on {{ $payment->paid_at->format('M d, Y \a\t H:i') }}
                    </p>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="card mb-8">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Booking Confirmation</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Property Info -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Property Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Property:</span>
                                    <span class="font-medium">{{ $payment->booking->kos->nama_kos }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Address:</span>
                                    <span class="font-medium text-right">{{ $payment->booking->kos->alamat }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Owner:</span>
                                    <span class="font-medium">{{ $payment->booking->kos->owner->name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Info -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Booking Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Booking ID:</span>
                                    <span class="font-medium">#{{ $payment->booking->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Check-in:</span>
                                    <span class="font-medium">{{ $payment->booking->tanggal_mulai->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium">{{ $payment->booking->durasi }} month{{ $payment->booking->durasi > 1 ? 's' : '' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Check-out:</span>
                                    <span class="font-medium">{{ $payment->booking->end_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="border-t border-gray-200 mt-6 pt-6">
                        <h4 class="font-medium text-gray-900 mb-3">Payment Summary</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monthly Rate:</span>
                                <span class="font-medium">Rp {{ number_format($payment->booking->kos->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">{{ $payment->booking->durasi }} month{{ $payment->booking->durasi > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium">{{ $payment->metode_pembayaran }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-900">Total Paid:</span>
                                    <span class="font-bold text-lg text-green-600">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card mb-8">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">What's Next?</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                1
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">Contact Property Owner</div>
                                <div class="text-sm text-gray-600">
                                    Reach out to {{ $payment->booking->kos->owner->name }} to coordinate your move-in details.
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                2
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">Prepare for Check-in</div>
                                <div class="text-sm text-gray-600">
                                    Your check-in date is {{ $payment->booking->tanggal_mulai->format('M d, Y') }}. 
                                    Make sure to bring required documents.
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                3
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">Keep This Receipt</div>
                                <div class="text-sm text-gray-600">
                                    Save this page or take a screenshot as proof of payment.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('bookings.show', $payment->booking) }}" class="btn-primary">
                    View Booking Details
                </a>
                <a href="{{ route('bookings.index') }}" class="btn-secondary">
                    View All Bookings
                </a>
                <button onclick="window.print()" class="btn-secondary">
                    Print Receipt
                </button>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
        }
    </style>
    @endpush
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Complete Payment
                </h2>
                <p class="text-gray-600 mt-1">Scan QR code to pay with QRIS</p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-6">
                <a href="{{ route('bookings.show', $payment->booking) }}" class="btn-secondary">
                    ← Back to Booking
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

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- QR Code Section -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900">QRIS Payment</h3>
                    </div>
                    <div class="card-body text-center">
                        <!-- Payment Status -->
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-blue-600 text-2xl">📱</span>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">Scan to Pay</h4>
                            <p class="text-sm text-gray-600">
                                Use your mobile banking or e-wallet app to scan this QR code
                            </p>
                        </div>

                        <!-- QR Code -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6 inline-block">
                            @if($payment->qr_code_data)
                                <div class="w-64 h-64 mx-auto flex items-center justify-center">
                                    {!! base64_decode($payment->qr_code_data) !!}
                                </div>
                            @else
                                <div class="w-64 h-64 mx-auto flex items-center justify-center bg-gray-100 rounded-lg">
                                    <div class="text-center">
                                        <div class="text-4xl mb-2">📱</div>
                                        <div class="text-sm text-gray-600">QR Code</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Payment Amount -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="text-sm text-gray-600 mb-1">Amount to Pay</div>
                            <div class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Timer -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="text-yellow-600">⏰</span>
                                <div>
                                    <div class="text-sm text-yellow-800 font-medium">Payment expires in</div>
                                    <div id="countdown-timer" class="text-lg font-bold text-yellow-900">
                                        --:--
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <!-- Simulate Payment Button (for testing) -->
                            <form action="{{ route('payments.complete', $payment) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="btn-primary w-full">
                                    ✓ Simulate Payment (Testing)
                                </button>
                            </form>
                            
                            <form action="{{ route('payments.cancel', $payment) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn-secondary w-full text-red-600 hover:text-red-700"
                                        onclick="return confirm('Are you sure you want to cancel this payment?')">
                                    Cancel Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payment Details & Instructions -->
                <div class="space-y-6">
                    <!-- Payment Details -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Reference:</span>
                                <span class="font-medium font-mono text-sm">{{ $payment->payment_reference }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking ID:</span>
                                <span class="font-medium">#{{ $payment->booking->id }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Property:</span>
                                <span class="font-medium">{{ $payment->booking->kos->nama_kos }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">{{ $payment->booking->durasi }} month{{ $payment->booking->durasi > 1 ? 's' : '' }}</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-900">Total Amount:</span>
                                    <span class="font-bold text-xl text-blue-600">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">How to Pay</h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-3 text-sm text-gray-700">
                                @foreach(explode("\n", $payment->payment_instructions) as $instruction)
                                    <div class="flex items-start space-x-3">
                                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                            {{ $loop->iteration }}
                                        </span>
                                        <span>{{ $instruction }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Supported Apps -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Supported Apps</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl mb-2">🏦</div>
                                    <div class="text-xs text-gray-600">Mobile Banking</div>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl mb-2">💳</div>
                                    <div class="text-xs text-gray-600">E-Wallet</div>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl mb-2">📱</div>
                                    <div class="text-xs text-gray-600">QRIS Apps</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Payment status checking and countdown timer
        let countdownInterval;
        let statusCheckInterval;
        
        function startCountdown() {
            const expiresAt = new Date('{{ $payment->expires_at->toISOString() }}');
            
            countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = expiresAt.getTime() - now;
                
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('countdown-timer').innerHTML = 'EXPIRED';
                    window.location.reload();
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('countdown-timer').innerHTML = 
                    String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            }, 1000);
        }
        
        function checkPaymentStatus() {
            fetch('{{ route("payments.status", $payment) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.is_completed) {
                        clearInterval(statusCheckInterval);
                        clearInterval(countdownInterval);
                        window.location.href = '{{ route("payments.success", $payment) }}';
                    } else if (data.is_expired) {
                        clearInterval(statusCheckInterval);
                        clearInterval(countdownInterval);
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error checking payment status:', error));
        }
        
        // Start countdown and status checking when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();
            statusCheckInterval = setInterval(checkPaymentStatus, 5000); // Check every 5 seconds
        });
        
        // Clean up intervals when page unloads
        window.addEventListener('beforeunload', function() {
            clearInterval(countdownInterval);
            clearInterval(statusCheckInterval);
        });
    </script>
    @endpush
</x-app-layout>

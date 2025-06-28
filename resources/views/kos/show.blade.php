<x-app-layout>
    <!-- Leaflet CSS and JS for free maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    {{ $ko->nama_kos }}
                </h2>
                <p class="text-gray-600 mt-1 flex items-center">
                    📍 {{ $ko->alamat }}
                </p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-6">
                <a href="{{ route('kos.index') }}" class="btn-secondary">
                    ← Back to Browse
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
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
                    <!-- Image Gallery -->
                    <div class="card">
                        <div class="relative h-96 bg-gray-200 overflow-hidden rounded-t-xl">
                            @if($ko->foto && count($ko->foto) > 0)
                                <img src="{{ asset('storage/' . $ko->foto[0]) }}"
                                     alt="{{ $ko->nama_kos }}"
                                     class="w-full h-full object-cover">

                                @if(count($ko->foto) > 1)
                                    <div class="absolute bottom-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
                                        +{{ count($ko->foto) - 1 }} more photos
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-black">
                                    <span class="text-6xl">🏠</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xl font-bold text-gray-900">Property Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Monthly Rent</h4>
                                    <p class="text-2xl font-bold text-blue-600">
                                        Rp {{ number_format($ko->harga, 0, ',', '.') }}
                                        <span class="text-sm text-gray-600 font-normal">/month</span>
                                    </p>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Status</h4>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $ko->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $ko->status ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2">Facilities & Amenities</h4>
                                <p class="text-gray-700 leading-relaxed">{{ $ko->fasilitas }}</p>
                            </div>

                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2">Owner Information</h4>
                                <div class="flex items-center space-x-3">
                                    @if($ko->owner)
                                        <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($ko->owner->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $ko->owner->name }}</p>
                                            <p class="text-sm text-gray-600">Property Owner</p>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold">
                                            ?
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Owner information not available</p>
                                            <p class="text-sm text-gray-600">Property Owner</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="card">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900">Reviews & Ratings</h3>
                                @if($totalReviews > 0)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-yellow-400 text-lg">⭐</span>
                                        <span class="font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                                        <span class="text-gray-600">({{ $totalReviews }} reviews)</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if($ko->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($ko->reviews->take(5) as $review)
                                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ $review->user ? substr($review->user->name, 0, 1) : '?' }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <h5 class="font-medium text-gray-900">{{ $review->user ? $review->user->name : 'Anonymous User' }}</h5>
                                                        <div class="flex items-center">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">⭐</span>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="text-gray-700 text-sm">{{ $review->ulasan }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $review->tanggal->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-gray-400 text-2xl">⭐</span>
                                    </div>
                                    <p class="text-gray-500">No reviews yet</p>
                                    <p class="text-sm text-gray-400 mt-1">Be the first to review this property</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Add Review Form (Only for tenants with confirmed bookings) -->
                    @auth
                        @if(Auth::user()->isTenant())
                            @php
                                $hasBooking = Auth::user()->bookings()
                                    ->where('kos_id', $ko->id)
                                    ->where('status_pemesanan', 'confirmed')
                                    ->exists();

                                $hasReview = Auth::user()->reviews()
                                    ->where('kos_id', $ko->id)
                                    ->exists();
                            @endphp


                            @if($hasBooking && !$hasReview)
                            <div class="card" x-data="{ rating: {{ old('rating', 0) }}, hoverRating: 0 }">
                                <div class="card-header">
                                    <h3 class="text-xl font-bold text-gray-900">Write a Review</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
                                        @csrf
                                        <input type="hidden" name="kos_id" value="{{ $ko->id }}">
                                        <input type="hidden" name="rating" x-model="rating">

                                        <div>
                                            <label class="form-label">Your Rating *</label>
                                            <p class="text-sm text-gray-500 mb-2">Click a star to set your rating.</p>
                                            <div class="flex items-center space-x-1" @mouseleave="hoverRating = 0">
                                                <template x-for="star in [1, 2, 3, 4, 5]">
                                                    <button type="button"
                                                            @click="rating = star"
                                                            @mouseenter="hoverRating = star"
                                                            class="text-3xl transition-colors duration-200"
                                                            :class="{
                                                                'text-yellow-400': star <= rating,
                                                                'text-yellow-300': star > rating && star <= hoverRating,
                                                                'text-gray-300': star > rating && star > hoverRating
                                                            }">
                                                        <span x-text="star <= rating || star <= hoverRating ? '★' : '☆'"></span>
                                                    </button>
                                                </template>
                                            </div>
                                            @error('rating')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="ulasan" class="form-label">Your Review *</label>
                                            <textarea id="ulasan"
                                                    name="ulasan"
                                                    rows="4"
                                                    class="form-input"
                                                    placeholder="Share your experience with this property..."
                                                    required>{{ old('ulasan') }}</textarea>
                                            @error('ulasan')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn-primary">
                                            Submit Review
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @elseif($hasReview)
                        <div class="card">
                            <div class="card-body">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <span class="text-blue-600 text-xl mr-3">✓</span>
                                        <div>
                                            <h4 class="font-medium text-blue-800">Review Submitted</h4>
                                            <p class="text-blue-700 text-sm mt-1">
                                                Thank you for reviewing this property! Your review helps other tenants make informed decisions.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                    @endauth
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Booking Card -->
                    @if($ko->status)
                        <div class="card top-24">
                            <div class="card-header">
                                <h3 class="text-lg font-bold text-gray-900">Book This Property</h3>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-6">
                                    <p class="text-3xl font-bold text-blue-600">
                                        Rp {{ number_format($ko->harga, 0, ',', '.') }}
                                    </p>
                                    <p class="text-gray-600">per month</p>
                                </div>

                                <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4" id="bookingForm">
                                    @csrf
                                    <input type="hidden" name="kos_id" value="{{ $ko->id }}">

                                    <div>
                                        <label class="form-label">Check-in Date</label>
                                        <input type="date"
                                               name="tanggal_mulai"
                                               class="form-input"
                                               min="{{ date('Y-m-d') }}"
                                               value="{{ old('tanggal_mulai') }}"
                                               required>
                                        @error('tanggal_mulai')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label">Duration (months)</label>
                                        <select name="durasi" class="form-input" required onchange="calculateTotal()">
                                            <option value="">Select duration</option>
                                            <option value="1" {{ old('durasi') == '1' ? 'selected' : '' }}>1 month</option>
                                            <option value="3" {{ old('durasi') == '3' ? 'selected' : '' }}>3 months</option>
                                            <option value="6" {{ old('durasi') == '6' ? 'selected' : '' }}>6 months</option>
                                            <option value="12" {{ old('durasi') == '12' ? 'selected' : '' }}>12 months</option>
                                            <option value="24" {{ old('durasi') == '24' ? 'selected' : '' }}>24 months</option>
                                        </select>
                                        @error('durasi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Total Price Display -->
                                    <div id="totalPrice" class="hidden bg-gray-50 p-3 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-700">Total Price:</span>
                                            <span class="font-bold text-lg text-blue-600" id="totalAmount">Rp 0</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn-primary w-full py-3">
                                        Book Now
                                    </button>
                                </form>
                                
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-500">
                                        You won't be charged yet. Review your booking details first.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-red-600 text-2xl">❌</span>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">Not Available</h3>
                                <p class="text-gray-600 text-sm">This property is currently not available for booking.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Map Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-bold text-gray-900">Location</h3>
                        </div>
                        <div class="card-body">
                            @if($ko->hasValidGoogleMapsUrl())
                                @php
                                    $embedUrl = $ko->getGoogleMapsEmbedUrl(600, 256);
                                    $leafletHtml = $ko->getLeafletMapHtml(600, 256);
                                @endphp

                                @if($embedUrl)
                                    <!-- Google Maps Embed (with API key) -->
                                    <div class="h-64 bg-gray-200 rounded-lg overflow-hidden mb-3">
                                        <iframe
                                            src="{{ $embedUrl }}"
                                            width="100%"
                                            height="256"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                            class="rounded-lg">
                                        </iframe>
                                    </div>
                                @elseif($leafletHtml)
                                    <!-- Free Leaflet Map (no API key required) -->
                                    <div class="mb-3">
                                        {!! $leafletHtml !!}
                                    </div>
                                    <div class="text-xs text-gray-500 mb-3">
                                        📍 Interactive map powered by OpenStreetMap (free alternative)
                                    </div>
                                @else
                                    <!-- Final fallback -->
                                    <div class="h-64 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center mb-3 border-2 border-dashed border-blue-300">
                                        <div class="text-center">
                                            <span class="text-4xl mb-2 block text-blue-500">🗺️</span>
                                            <p class="text-blue-700 text-sm font-medium">Location Available</p>
                                            <p class="text-blue-600 text-xs">Click "Open in Google Maps" to view location</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Map Actions -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <a href="{{ $ko->getGoogleMapsUrl() }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        🗺️ Open in Google Maps
                                    </a>
                                    <button onclick="getDirections()"
                                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        🧭 Get Directions
                                    </button>
                                </div>

                            @else
                                <!-- No location data -->
                                <div class="h-48 bg-gray-100 rounded-lg flex items-center justify-center mb-3">
                                    <div class="text-center">
                                        <span class="text-4xl mb-2 block text-gray-400">📍</span>
                                        <p class="text-gray-500 text-sm">Location information not available</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Address -->
                            <div class="bg-gray-50 rounded-lg p-3">
                                <h4 class="font-medium text-gray-900 mb-1">Address</h4>
                                <p class="text-gray-700 text-sm">{{ $ko->alamat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateTotal() {
            const durasi = document.querySelector('select[name="durasi"]').value;
            const monthlyPrice = {{ $ko->harga }};
            const totalPriceDiv = document.getElementById('totalPrice');
            const totalAmountSpan = document.getElementById('totalAmount');

            if (durasi) {
                const total = monthlyPrice * parseInt(durasi);
                totalAmountSpan.textContent = 'Rp ' + total.toLocaleString('id-ID');
                totalPriceDiv.classList.remove('hidden');
            } else {
                totalPriceDiv.classList.add('hidden');
            }
        }

        // Auto-calculate on page load if duration is already selected
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });

        // Star rating functionality
        function setRating(rating) {
            document.getElementById('rating').value = rating;

            // Update star display
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        // Initialize rating on page load if there's an old value
        document.addEventListener('DOMContentLoaded', function() {
            const oldRating = {{ old('rating', 0) }};
            if (oldRating > 0) {
                setRating(oldRating);
            }
        });

        // Get directions function
        function getDirections() {
            @if($ko->hasValidGoogleMapsUrl())
                const mapUrl = "{{ $ko->getGoogleMapsUrl() }}";
                const directionsUrl = mapUrl.replace('maps?q=', 'maps/dir//');
                window.open(directionsUrl, '_blank');
            @endif
        }
    </script>
</x-app-layout>

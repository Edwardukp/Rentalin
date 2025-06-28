<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    {{ $kos->nama_kos }}
                </h2>
                <p class="text-gray-600 mt-1 flex items-center">
                    📍 {{ $kos->alamat }}
                </p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-6">
                {{-- <a href="{{ route('owner.kos.edit', $kos) }}" class="btn-primary">
                    ✏️ Edit Property
                </a> --}}
                <a href="{{ route('owner.kos.index') }}" class="btn-secondary">
                    ← Back to Properties
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Property Photos -->
                    @if($kos->foto && count($kos->foto) > 0)
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($kos->foto as $index => $photo)
                                    <div class="{{ $index === 0 ? 'md:col-span-2' : '' }}">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             alt="{{ $kos->nama_kos }}"
                                             class="w-full {{ $index === 0 ? 'h-64' : 'h-32' }} object-cover {{ $index === 0 ? 'rounded-t-lg' : 'rounded-lg' }}">
                                    </div>
                                    @if($index >= 3) @break @endif
                                @endforeach
                            </div>
                            @if(count($kos->foto) > 4)
                                <div class="p-4 text-center">
                                    <span class="text-gray-600">+{{ count($kos->foto) - 4 }} more photos</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Property Details -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xl font-bold text-gray-900">Property Details</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Basic Information</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Property Name:</span>
                                            <span class="font-medium">{{ $kos->nama_kos }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Monthly Rent:</span>
                                            <span class="font-bold text-green-600">Rp {{ number_format($kos->harga, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Status:</span>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $kos->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $kos->status ? 'Available' : 'Not Available' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Statistics</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Total Bookings:</span>
                                            <span class="font-medium">{{ $kos->bookings_count ?? $kos->bookings->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Total Reviews:</span>
                                            <span class="font-medium">{{ $totalReviews }}</span>
                                        </div>
                                        @if($averageRating)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Average Rating:</span>
                                            <div class="flex items-center space-x-1">
                                                <span class="text-yellow-400">⭐</span>
                                                <span class="font-medium">{{ number_format($averageRating, 1) }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Full Address</h4>
                                <p class="text-gray-700">{{ $kos->alamat }}</p>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Facilities & Amenities</h4>
                                <p class="text-gray-700 leading-relaxed">{{ $kos->fasilitas }}</p>
                            </div>

                            <!-- Location Information -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Location Information</h4>

                                @if($kos->hasValidGoogleMapsUrl())
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-green-600">✅</span>
                                            <span class="text-gray-700">Google Maps integration enabled</span>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ $kos->getGoogleMapsUrl() }}"
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                🗺️ View on Google Maps
                                            </a>
                                            <a href="{{ route('owner.kos.edit', $kos) }}"
                                               class="inline-flex items-center px-3 py-1 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors">
                                                ✏️ Edit Location
                                            </a>
                                        </div>

                                        @if($kos->getCoordinatesFromGoogleMaps())
                                            @php $coords = $kos->getCoordinatesFromGoogleMaps(); @endphp
                                            <div class="text-xs text-gray-500">
                                                Coordinates: {{ number_format($coords['latitude'], 6) }}, {{ number_format($coords['longitude'], 6) }}
                                            </div>
                                        @endif
                                    </div>

                                @else
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-red-600">❌</span>
                                            <span class="text-gray-700">No location data available</span>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('owner.kos.edit', $kos) }}"
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                📍 Add Location
                                            </a>
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            Adding location information will help tenants find your property more easily.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Bookings -->
                    @if($kos->bookings->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xl font-bold text-gray-900">Recent Bookings</h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-4">
                                @foreach($kos->bookings->take(5) as $booking)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr($booking->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $booking->tanggal_mulai->format('M d, Y') }} - {{ $booking->durasi }} months</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($booking->status_pemesanan) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($kos->bookings->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('owner.bookings.index', ['kos_id' => $kos->id]) }}" class="text-blue-600 hover:text-blue-800">
                                        View all bookings →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="card-body space-y-3">
                            <a href="{{ route('owner.kos.edit', $kos) }}" class="w-full btn-primary text-center">
                                ✏️ Edit Property
                            </a>
                            <button onclick="toggleStatus()" class="w-full btn-secondary">
                                {{ $kos->status ? '🔒 Mark Unavailable' : '🔓 Mark Available' }}
                            </button>
                            <form action="{{ route('owner.kos.destroy', $kos) }}" 
                                  method="POST" 
                                  class="w-full"
                                  onsubmit="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-colors duration-200">
                                    🗑️ Delete Property
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Property Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Summary</h3>
                        </div>
                        <div class="card-body space-y-3">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">Rp {{ number_format($kos->harga, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-600">per month</div>
                            </div>
                            
                            @if($averageRating)
                            <div class="text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <span class="text-yellow-400 text-xl">⭐</span>
                                    <span class="text-lg font-bold">{{ number_format($averageRating, 1) }}</span>
                                </div>
                                <div class="text-sm text-gray-600">{{ $totalReviews }} reviews</div>
                            </div>
                            @endif
                            
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600">{{ $kos->bookings->count() }}</div>
                                <div class="text-sm text-gray-600">total bookings</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleStatus() {
            // This would typically make an AJAX request to toggle the status
            // For now, we'll redirect to edit page
            window.location.href = "{{ route('owner.kos.edit', $kos) }}";
        }
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Browse Kos Properties 🏠
                </h2>
                <p class="text-gray-600 mt-1">Find your perfect place to stay</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="card mb-8">
                <div class="card-body">
                    <form method="GET" action="{{ route('kos.index') }}" class="space-y-6" id="searchForm">
                        <!-- Main Search Bar -->
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search by name, location, or facilities..."
                                   class="form-input pl-12 text-lg py-4">
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                🔍
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Count and Active Filters -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600">
                        Found <strong>{{ $kos->total() }}</strong> properties
                        @if(request('search'))
                            for "<strong>{{ request('search') }}</strong>"
                        @endif
                    </p>

                    <!-- Active Filters -->
                    @if(request()->hasAny(['search', 'location', 'min_price', 'max_price', 'min_rating', 'facilities', 'sort_by']))
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Active filters:</span>
                            <div class="flex flex-wrap gap-2">
                                @if(request('location'))
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                        📍 {{ request('location') }}
                                    </span>
                                @endif

                                @if(request('min_price') || request('max_price'))
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                        💰 Rp {{ number_format(request('min_price', 0)) }} - {{ request('max_price') ? 'Rp ' . number_format(request('max_price')) : '∞' }}
                                    </span>
                                @endif

                                @if(request('min_rating'))
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                        ⭐ {{ request('min_rating') }}+ stars
                                    </span>
                                @endif

                                @if(request('facilities'))
                                    @foreach(request('facilities') as $facility)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                            🏠 {{ $facility }}
                                        </span>
                                    @endforeach
                                @endif

                                @if(request('sort_by') && request('sort_by') !== 'latest')
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                        📊 {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kos Grid -->
            @if($kos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($kos as $property)
                        <div class="card group hover:shadow-2xl transition-all duration-300">
                            <!-- Image -->
                            <div class="relative h-48 bg-gray-200 overflow-hidden">
                                @if($property->foto && count($property->foto) > 0)
                                    <img src="{{ asset('storage/' . $property->foto[0]) }}" 
                                         alt="{{ $property->nama_kos }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <span class="text-4xl text-gray-400">🏠</span>
                                    </div>
                                @endif
                                
                                <!-- Price Badge -->
                                <div class="absolute top-4 right-4 bg-white shadow-sm px-3 py-1 rounded-full border border-gray-200">
                                    <span class="font-bold text-gray-900">Rp {{ number_format($property->harga, 0, ',', '.') }}</span>
                                    <span class="text-xs text-gray-600">/month</span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <!-- Title and Location -->
                                <div class="mb-3">
                                    <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $property->nama_kos }}</h3>
                                    <p class="text-gray-600 text-sm flex items-center">
                                        📍 {{ Str::limit($property->alamat, 50) }}
                                    </p>
                                </div>
                                
                                <!-- Facilities -->
                                <div class="mb-4">
                                    <p class="text-gray-700 text-sm">
                                        {{ Str::limit($property->fasilitas, 80) }}
                                    </p>
                                </div>
                                
                                <!-- Rating and Reviews -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        @if($property->reviews->count() > 0)
                                            <div class="flex items-center">
                                                <span class="text-yellow-400">⭐</span>
                                                <span class="font-medium text-gray-900 ml-1">
                                                    {{ number_format($property->averageRating(), 1) }}
                                                </span>
                                                <span class="text-gray-600 text-sm ml-1">
                                                    ({{ $property->reviews->count() }} reviews)
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-sm">No reviews yet</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Action Button -->
                                <a href="{{ route('kos.show', $property) }}" 
                                   class="btn-primary w-full text-center block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $kos->withQueryString()->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-gray-400 text-4xl">🔍</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No properties found</h3>
                        <p class="text-gray-600 mb-6">
                            @if(request()->hasAny(['search', 'min_price', 'max_price']))
                                Try adjusting your search criteria or browse all available properties.
                            @else
                                There are no kos properties available at the moment.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'min_price', 'max_price']))
                            <a href="{{ route('kos.index') }}" class="btn-secondary">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleFilters() {
            const filters = document.getElementById('advancedFilters');
            const icon = document.getElementById('filterToggleIcon');

            if (filters.classList.contains('hidden')) {
                filters.classList.remove('hidden');
                icon.textContent = '▲';
            } else {
                filters.classList.add('hidden');
                icon.textContent = '▼';
            }
        }

        function toggleFacilities() {
            const dropdown = document.getElementById('facilitiesDropdown');
            dropdown.classList.toggle('hidden');
        }

        function setPriceRange(min, max) {
            document.querySelector('input[name="min_price"]').value = min;
            document.querySelector('input[name="max_price"]').value = max || '';
            document.getElementById('searchForm').submit();
        }

        // Close facilities dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('facilitiesDropdown');
            const button = event.target.closest('button');

            if (!button || button.getAttribute('onclick') !== 'toggleFacilities()') {
                dropdown.classList.add('hidden');
            }
        });

        // Show advanced filters if any advanced filter is active
        document.addEventListener('DOMContentLoaded', function() {
            const hasAdvancedFilters = {{ request()->hasAny(['location', 'min_price', 'max_price', 'min_rating', 'facilities']) ? 'true' : 'false' }};
            if (hasAdvancedFilters) {
                toggleFilters();
            }
        });
    </script>
</x-app-layout>

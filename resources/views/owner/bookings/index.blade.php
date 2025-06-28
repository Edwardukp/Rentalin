<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Property Bookings 📋
                </h2>
                <p class="text-gray-600 mt-1">Manage bookings for your properties</p>
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

            <!-- Filters -->
            <div class="card mb-6">
                <div class="card-body">
                    <form method="GET" action="{{ route('owner.bookings.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-48">
                            <label class="form-label">Filter by Property</label>
                            <select name="kos_id" class="form-input" onchange="this.form.submit()">
                                <option value="">All Properties</option>
                                @foreach($properties as $id => $name)
                                    <option value="{{ $id }}" {{ request('kos_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex-1 min-w-48">
                            <label class="form-label">Filter by Status</label>
                            <select name="status" class="form-input" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        
                        @if(request()->hasAny(['kos_id', 'status']))
                            <div class="flex items-end">
                                <a href="{{ route('owner.bookings.index') }}" class="btn-secondary">
                                    Clear Filters
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            @if($bookings->count() > 0)
                <!-- Bookings List -->
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="card">
                            <div class="card-body">
                                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                                    <!-- Property Info -->
                                    <div class="lg:col-span-2">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                                @if($booking->kos->foto && count($booking->kos->foto) > 0)
                                                    <img src="{{ asset('storage/' . $booking->kos->foto[0]) }}" 
                                                         alt="{{ $booking->kos->nama_kos }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-black">
                                                        <span class="text-lg">🏠</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-900 mb-1">{{ $booking->kos->nama_kos }}</h3>
                                                <p class="text-gray-600 text-sm">{{ Str::limit($booking->kos->alamat, 50) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tenant Info -->
                                    <div class="lg:col-span-1">
                                        <div class="text-sm">
                                            <span class="text-gray-600">Tenant:</span>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <div class="w-6 h-6 bg-black rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($booking->user->name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $booking->user->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Booking Details -->
                                    <div class="lg:col-span-1">
                                        <div class="text-sm space-y-1">
                                            <div>
                                                <span class="text-gray-600">Check-in:</span>
                                                <p class="font-medium">{{ $booking->tanggal_mulai->format('M d, Y') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Duration:</span>
                                                <p class="font-medium">{{ $booking->durasi }} month{{ $booking->durasi > 1 ? 's' : '' }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Total:</span>
                                                <p class="font-bold text-blue-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Status & Actions -->
                                    <div class="lg:col-span-1">
                                        <div class="flex flex-col space-y-3">
                                            <span class="px-3 py-1 rounded-full text-sm font-medium text-center
                                                {{ $booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($booking->status_pemesanan) }}
                                            </span>
                                            
                                            <div class="flex flex-col space-y-2">
                                                <a href="{{ route('owner.bookings.show', $booking) }}" 
                                                   class="btn-secondary text-center text-sm py-1">
                                                    View Details
                                                </a>
                                                
                                                @if($booking->status_pemesanan === 'pending')
                                                    <form action="{{ route('owner.bookings.updateStatus', $booking) }}" 
                                                          method="POST" class="space-y-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status_pemesanan" value="confirmed">
                                                        <button type="submit" 
                                                                class="w-full btn-primary text-sm py-1">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('owner.bookings.updateStatus', $booking) }}" 
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to reject this booking?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status_pemesanan" value="canceled">
                                                        <button type="submit" 
                                                                class="w-full btn-secondary text-red-600 hover:text-red-700 hover:bg-red-50 text-sm py-1">
                                                            Reject
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-gray-400 text-4xl">📋</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No bookings found</h3>
                        <p class="text-gray-600 mb-6">
                            @if(request()->hasAny(['kos_id', 'status']))
                                No bookings match your current filters. Try adjusting your search criteria.
                            @else
                                You don't have any bookings yet. Bookings will appear here once tenants start booking your properties.
                            @endif
                        </p>
                        @if(request()->hasAny(['kos_id', 'status']))
                            <a href="{{ route('owner.bookings.index') }}" class="btn-secondary">
                                Clear Filters
                            </a>
                        @else
                            <a href="{{ route('owner.kos.index') }}" class="btn-primary">
                                Manage Properties
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

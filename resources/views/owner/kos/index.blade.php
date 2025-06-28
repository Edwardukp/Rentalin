<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    My Properties 🏢
                </h2>
                <p class="text-gray-600 mt-1">Manage your kos properties</p>
            </div>
            <div class="hidden sm:flex items-center space-x-3 px-3">
                <a href="{{ route('owner.kos.create') }}" class="btn-primary">
                    + Add New Property
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

            @if($kos->count() > 0)
                <!-- Properties Grid -->
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
                                    <div class="w-full h-full flex items-center justify-center bg-black">
                                        <span class="text-4xl">🏠</span>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $property->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $property->status ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <!-- Title and Price -->
                                <div class="mb-3">
                                    <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $property->nama_kos }}</h3>
                                    <p class="text-xl font-bold text-blue-600">
                                        Rp {{ number_format($property->harga, 0, ',', '.') }}
                                        <span class="text-sm text-gray-600 font-normal">/month</span>
                                    </p>
                                </div>
                                
                                <!-- Location -->
                                <p class="text-gray-600 text-sm mb-3 flex items-center">
                                    📍 {{ Str::limit($property->alamat, 50) }}
                                </p>

                                <!-- Owner Info (for admin) -->
                                @if(auth()->user()->email === 'admin@rentalin.com' || auth()->user()->name === 'Admin User')
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 mb-3">
                                        <p class="text-xs text-blue-800 font-medium">
                                            👤 Owner: {{ $property->owner->name ?? 'Unknown' }}
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-4 mb-4 text-center">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-lg font-bold text-gray-900">{{ $property->bookings_count }}</p>
                                        <p class="text-xs text-gray-600">Bookings</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-lg font-bold text-gray-900">{{ $property->reviews_count }}</p>
                                        <p class="text-xs text-gray-600">Reviews</p>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    @can('view', $property)
                                        <a href="{{ route('owner.kos.show', $property) }}"
                                           class="flex-1 btn-secondary text-center text-sm py-2">
                                            View
                                        </a>
                                    @endcan

                                    @can('update', $property)
                                        <a href="{{ route('owner.kos.edit', $property) }}"
                                           class="flex-1 btn-primary text-center text-sm py-2">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete', $property)
                                        <form action="{{ route('owner.kos.destroy', $property) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this property?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors duration-200">
                                                🗑️
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $kos->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-gray-400 text-4xl">🏢</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No properties yet</h3>
                        <p class="text-gray-600 mb-6">
                            Start by adding your first kos property to begin earning rental income.
                        </p>
                        <a href="{{ route('owner.kos.create') }}" class="btn-primary">
                            + Add Your First Property
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

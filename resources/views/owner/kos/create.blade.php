<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Add New Property 🏠
                </h2>
                <p class="text-gray-600 mt-1">List your kos property for rent</p>
            </div>
            <div class="hidden sm:flex items-center space-x-3">
                <a href="{{ route('owner.kos.index') }}" class="btn-secondary">
                    ← Back to Properties
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('owner.kos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <!-- Property Name -->
                        <div>
                            <label for="nama_kos" class="form-label">Property Name *</label>
                            <input type="text" 
                                   id="nama_kos" 
                                   name="nama_kos" 
                                   value="{{ old('nama_kos') }}"
                                   class="form-input" 
                                   placeholder="e.g., Kos Melati Indah"
                                   required>
                            @error('nama_kos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="alamat" class="form-label">Full Address *</label>
                            <textarea id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      class="form-input" 
                                      placeholder="Enter the complete address including street, district, city"
                                      required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="harga" class="form-label">Monthly Rent (Rp) *</label>
                            <input type="number"
                                   id="harga"
                                   name="harga"
                                   value="{{ old('harga') }}"
                                   class="form-input"
                                   placeholder="e.g., 1500000"
                                   min="0"
                                   max="999999999999.99"
                                   step="0.01"
                                   required>
                            <p class="mt-1 text-sm text-gray-600">
                                Enter the monthly rent in Indonesian Rupiah (Rp). Example: 1500000 for Rp 1.5 million.
                            </p>
                            @error('harga')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Facilities & Amenities -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900">Facilities & Amenities</h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <label for="fasilitas" class="form-label">Facilities Description *</label>
                            <textarea id="fasilitas" 
                                      name="fasilitas" 
                                      rows="5"
                                      class="form-input" 
                                      placeholder="Describe all facilities and amenities available (e.g., WiFi, AC, private bathroom, kitchen, parking, etc.)"
                                      required>{{ old('fasilitas') }}</textarea>
                            @error('fasilitas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900">Property Photos</h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <label for="foto" class="form-label">Upload Photos</label>
                            <input type="file" 
                                   id="foto" 
                                   name="foto[]" 
                                   multiple
                                   accept="image/*"
                                   class="form-input">
                            <p class="mt-1 text-sm text-gray-600">
                                You can upload multiple photos. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB per photo.
                            </p>
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('foto.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location (Optional) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900">Location (Optional)</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <!-- Google Maps URL Input -->
                            <div>
                                <label for="google_maps_url" class="form-label">Google Maps Link</label>
                                <input type="url"
                                       id="google_maps_url"
                                       name="google_maps_url"
                                       value="{{ old('google_maps_url') }}"
                                       class="form-input"
                                       placeholder="https://maps.google.com/...">
                                @error('google_maps_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-600">
                                    Paste a Google Maps link to your property location. This will help tenants find your property easily.
                                </p>
                            </div>


                        </div>

                        <!-- Instructions -->
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">How to get a Google Maps link:</h4>
                            <ol class="text-sm text-blue-800 space-y-1">
                                <li>1. Go to <a href="https://maps.google.com" target="_blank" class="underline">Google Maps</a></li>
                                <li>2. Search for your property address</li>
                                <li>3. Click on the location pin</li>
                                <li>4. Click "Share" and copy the link</li>
                                <li>5. Paste the link in the field above</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900">Availability Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="status" 
                                   name="status" 
                                   value="1"
                                   {{ old('status', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <label for="status" class="ml-2 text-sm text-gray-700">
                                Property is available for rent
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">
                            Uncheck this if the property is not ready for rent yet.
                        </p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('owner.kos.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Create Property
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const googleMapsInput = document.getElementById('google_maps_url');

            // Validate Google Maps URL
            googleMapsInput.addEventListener('blur', function() {
                const url = this.value.trim();
                if (url && !isValidGoogleMapsUrl(url)) {
                    this.classList.add('border-red-500');
                    showValidationMessage(this, 'Please enter a valid Google Maps URL');
                } else {
                    this.classList.remove('border-red-500');
                    hideValidationMessage(this);
                }
            });

            function isValidGoogleMapsUrl(url) {
                const validDomains = [
                    'maps.google.com',
                    'www.google.com/maps',
                    'google.com/maps',
                    'maps.app.goo.gl',
                    'goo.gl/maps'
                ];

                try {
                    const urlObj = new URL(url);
                    return validDomains.some(domain => urlObj.hostname.includes(domain));
                } catch {
                    return false;
                }
            }

            function showValidationMessage(input, message) {
                hideValidationMessage(input);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'mt-1 text-sm text-red-600 validation-error';
                errorDiv.textContent = message;
                input.parentNode.appendChild(errorDiv);
            }

            function hideValidationMessage(input) {
                const existingError = input.parentNode.querySelector('.validation-error');
                if (existingError) {
                    existingError.remove();
                }
            }
        });
    </script>
</x-app-layout>

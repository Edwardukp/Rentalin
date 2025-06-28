<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Analytics & Reports 📊
                </h2>
                <p class="text-gray-600 mt-1">Track your rental business performance</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Coming Soon -->
            <div class="card">
                <div class="card-body text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-gray-400 text-4xl">📊</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Analytics Coming Soon</h3>
                    <p class="text-gray-600 mb-6">
                        We're working on comprehensive analytics and reporting features. 
                        This will include revenue tracking, occupancy rates, booking trends, and more.
                    </p>
                    <a href="{{ route('owner.kos.index') }}" class="btn-primary">
                        Back to Properties
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

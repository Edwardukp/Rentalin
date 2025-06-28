<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Owner\KosController as OwnerKosController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kos routes for tenants (browsing)
    Route::resource('kos', KosController::class)->only(['index', 'show']);

    // Booking routes for tenants
    Route::resource('bookings', BookingController::class)->only(['index', 'store', 'show']);
    Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Payment routes for tenants
    Route::get('bookings/{booking}/payment/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('payments/{payment}/status', [PaymentController::class, 'checkStatus'])->name('payments.status');
    Route::post('payments/{payment}/complete', [PaymentController::class, 'complete'])->name('payments.complete');
    Route::get('payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::delete('payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

    // Review routes for tenants
    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Owner routes
    Route::prefix('owner')->name('owner.')->middleware('role:owner')->group(function () {
        Route::resource('kos', OwnerKosController::class)->parameters(['kos' => 'kos']);
        Route::get('bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [OwnerBookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}/status', [OwnerBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
        Route::get('analytics', function () {
            return view('owner.analytics');
        })->name('analytics');
    });
});

require __DIR__.'/auth.php';

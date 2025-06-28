<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\QrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $qrisService;

    public function __construct(QrisService $qrisService)
    {
        $this->qrisService = $qrisService;
    }

    /**
     * Create a new payment for a booking
     */
    public function create(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Check if booking is confirmed
        if (!$booking->isConfirmed()) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Payment is only available for confirmed bookings.');
        }

        // Check if payment already exists
        if ($booking->payment) {
            return redirect()->route('payments.show', $booking->payment);
        }

        try {
            DB::beginTransaction();

            // Generate payment reference
            $paymentReference = Payment::generatePaymentReference();

            // Create payment record
            $payment = $booking->payment()->create([
                'metode_pembayaran' => 'QRIS',
                'jumlah' => $booking->total_harga,
                'status' => 'pending',
                'tanggal' => now(),
                'payment_reference' => $paymentReference,
                'expires_at' => now()->addMinutes(30), // 30 minutes expiration
                'payment_instructions' => $this->getPaymentInstructions(),
                'qris_metadata' => [
                    'merchant_name' => config('app.name', 'Rentalin'),
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_harga,
                    'created_at' => now()->toISOString(),
                ]
            ]);

            // Generate QRIS QR code
            $qrCodeData = $this->qrisService->generateQRCode($payment);
            $payment->update(['qr_code_data' => $qrCodeData]);

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Payment created successfully. Please scan the QR code to complete payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Failed to create payment. Please try again.');
        }
    }

    /**
     * Display the payment page with QR code
     */
    public function show(Payment $payment)
    {
        // Check if user owns this payment
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this payment.');
        }

        // Check if payment is expired
        if ($payment->isExpired()) {
            $payment->update(['status' => 'expired']);
            return view('payments.expired', compact('payment'));
        }

        // Load booking relationship
        $payment->load('booking.kos');

        return view('payments.show', compact('payment'));
    }

    /**
     * Check payment status (AJAX endpoint)
     */
    public function checkStatus(Payment $payment)
    {
        // Check if user owns this payment
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Refresh payment status from database
        $payment->refresh();

        // Check if payment is expired
        if ($payment->isExpired() && $payment->status === 'pending') {
            $payment->update(['status' => 'expired']);
        }

        return response()->json([
            'status' => $payment->status,
            'is_completed' => $payment->isCompleted(),
            'is_expired' => $payment->isExpired(),
            'time_remaining' => $payment->time_remaining,
            'expires_at' => $payment->expires_at?->toISOString(),
        ]);
    }

    /**
     * Simulate payment completion (for testing purposes)
     * In production, this would be called by payment gateway webhook
     */
    public function complete(Payment $payment)
    {
        // Check if user owns this payment
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this payment.');
        }

        // Check if payment is still valid
        if (!$payment->isValid()) {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Payment is no longer valid or has expired.');
        }

        try {
            DB::beginTransaction();

            // Update payment status
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'transaction_id' => 'TXN-' . strtoupper(uniqid()) . '-' . time(),
            ]);

            // Update booking status if needed
            // Note: Booking status might already be confirmed by owner
            // We don't change it here to avoid conflicts

            DB::commit();

            return redirect()->route('payments.success', $payment)
                ->with('success', 'Payment completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Failed to complete payment. Please try again.');
        }
    }

    /**
     * Show payment success page
     */
    public function success(Payment $payment)
    {
        // Check if user owns this payment
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this payment.');
        }

        // Check if payment is actually completed
        if (!$payment->isCompleted()) {
            return redirect()->route('payments.show', $payment);
        }

        $payment->load('booking.kos');

        return view('payments.success', compact('payment'));
    }

    /**
     * Cancel a pending payment
     */
    public function cancel(Payment $payment)
    {
        // Check if user owns this payment
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this payment.');
        }

        // Check if payment can be cancelled
        if (!$payment->isPending()) {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Only pending payments can be cancelled.');
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('bookings.show', $payment->booking)
            ->with('success', 'Payment has been cancelled.');
    }

    /**
     * Get payment instructions text
     */
    private function getPaymentInstructions()
    {
        return "1. Open your mobile banking or e-wallet app\n" .
               "2. Select 'Scan QR Code' or 'QRIS' option\n" .
               "3. Scan the QR code displayed on this page\n" .
               "4. Verify the payment amount and merchant details\n" .
               "5. Enter your PIN or authenticate as required\n" .
               "6. Complete the payment\n" .
               "7. Wait for payment confirmation";
    }
}

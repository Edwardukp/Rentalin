<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessExpiredPayments implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing expired payments...');

        // Find all pending payments that have expired
        $expiredPayments = Payment::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->with('booking')
            ->get();

        $processedCount = 0;

        foreach ($expiredPayments as $payment) {
            try {
                // Update payment status to expired
                $payment->update(['status' => 'expired']);

                Log::info("Payment {$payment->id} marked as expired", [
                    'payment_id' => $payment->id,
                    'booking_id' => $payment->booking_id,
                    'amount' => $payment->jumlah,
                    'expired_at' => $payment->expires_at,
                ]);

                $processedCount++;

            } catch (\Exception $e) {
                Log::error("Failed to process expired payment {$payment->id}", [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info("Processed {$processedCount} expired payments");
    }
}

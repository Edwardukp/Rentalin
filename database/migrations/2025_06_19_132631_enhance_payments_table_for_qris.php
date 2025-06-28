<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Change status from boolean to enum for better status tracking
            $table->enum('status', ['pending', 'completed', 'failed', 'expired'])->default('pending')->change();

            // Add QRIS-specific fields
            $table->text('qr_code_data')->nullable()->after('transaction_id');
            $table->string('payment_reference')->unique()->nullable()->after('qr_code_data');
            $table->timestamp('expires_at')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('expires_at');
            $table->text('payment_instructions')->nullable()->after('paid_at');
            $table->json('qris_metadata')->nullable()->after('payment_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove QRIS-specific fields
            $table->dropColumn([
                'qr_code_data',
                'payment_reference',
                'expires_at',
                'paid_at',
                'payment_instructions',
                'qris_metadata'
            ]);

            // Revert status back to boolean
            $table->boolean('status')->default(0)->change();
        });
    }
};

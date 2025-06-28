<?php

namespace App\Services;

use App\Models\Payment;

class QrisService
{
    /**
     * Generate QRIS QR Code for payment
     */
    public function generateQRCode(Payment $payment)
    {
        // For now, use the fallback method until we properly configure QR code generation
        return $this->generateFallbackQRCode($payment);
    }

    /**
     * Generate QRIS-compliant payment string
     * This is a simplified version - in production you would use proper QRIS specification
     */
    private function generateQrisString(Payment $payment)
    {
        $merchantName = config('app.name', 'Rentalin');
        $merchantCity = config('app.city', 'Jakarta');
        $merchantId = config('app.merchant_id', '1234567890');
        
        // Simplified QRIS format (in production, use proper QRIS EMV specification)
        $qrisData = [
            'merchant_name' => $merchantName,
            'merchant_city' => $merchantCity,
            'merchant_id' => $merchantId,
            'amount' => number_format($payment->jumlah, 2, '.', ''),
            'currency' => 'IDR',
            'reference' => $payment->payment_reference,
            'booking_id' => $payment->booking_id,
            'timestamp' => $payment->created_at->timestamp,
        ];
        
        // For demo purposes, we'll create a JSON string
        // In production, this should follow QRIS EMV specification
        return json_encode($qrisData);
    }

    /**
     * Validate QRIS payment data
     */
    public function validateQrisData($qrisString)
    {
        try {
            $data = json_decode($qrisString, true);
            
            return isset($data['merchant_name']) && 
                   isset($data['amount']) && 
                   isset($data['reference']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get merchant information for QRIS
     */
    public function getMerchantInfo()
    {
        return [
            'name' => config('app.name', 'Rentalin'),
            'city' => config('app.city', 'Jakarta'),
            'merchant_id' => config('app.merchant_id', '1234567890'),
            'category' => '7299', // Real Estate Services
        ];
    }

    /**
     * Format amount for QRIS (Indonesian Rupiah)
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * Generate QR code as SVG (alternative format)
     */
    public function generateQRCodeSVG(Payment $payment)
    {
        // For now, use the fallback method
        return $this->generateFallbackQRCode($payment);
    }

    /**
     * Generate a fallback QR code representation
     */
    private function generateFallbackQRCode(Payment $payment)
    {
        // Create a simple placeholder image as base64
        $width = 300;
        $height = 300;

        // Create a simple SVG placeholder
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="100%" fill="white" stroke="black" stroke-width="2"/>
            <text x="50%" y="40%" text-anchor="middle" font-family="Arial" font-size="14" fill="black">
                QRIS Payment
            </text>
            <text x="50%" y="55%" text-anchor="middle" font-family="Arial" font-size="12" fill="black">
                Rp ' . number_format($payment->jumlah, 0, ',', '.') . '
            </text>
            <text x="50%" y="70%" text-anchor="middle" font-family="Arial" font-size="10" fill="gray">
                Ref: ' . $payment->payment_reference . '
            </text>
        </svg>';

        return base64_encode($svg);
    }
}

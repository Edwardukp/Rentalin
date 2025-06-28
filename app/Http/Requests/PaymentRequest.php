<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $booking = $this->route('booking');

        // Check if user owns the booking
        if (!$booking || $booking->user_id !== Auth::id()) {
            return false;
        }

        // Check if booking is confirmed
        if (!$booking->isConfirmed()) {
            return false;
        }

        // Check if payment doesn't already exist or is expired/failed
        if ($booking->payment && $booking->payment->isValid()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // No additional validation needed for payment creation
            // All validation is done in authorize() method
        ];
    }

    /**
     * Get custom error messages for validation failures.
     */
    public function messages(): array
    {
        return [
            'authorize' => 'You are not authorized to create a payment for this booking.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        $booking = $this->route('booking');

        if (!$booking) {
            abort(404, 'Booking not found.');
        }

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this booking.');
        }

        if (!$booking->isConfirmed()) {
            abort(422, 'Payment is only available for confirmed bookings.');
        }

        if ($booking->payment && $booking->payment->isValid()) {
            abort(422, 'A valid payment already exists for this booking.');
        }

        abort(403, 'You are not authorized to create a payment for this booking.');
    }
}

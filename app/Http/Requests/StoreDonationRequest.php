<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
   public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'center_id' => 'required|exists:centers,id',
            'donation_date' => 'required|date|before_or_equal:today',
            'observed_blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'test_result' => 'required|string|in:accepted,rejected',

            'medical_notes' => 'nullable|string|max:1000',
        ];
    }
}

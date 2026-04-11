<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBloodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'center_id'       => 'required|exists:centers,id',
            'blood_group'     => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_needed' => 'required|integer|min:1',
            'priority'        => 'required',
            'hopital_id'      =>'required|exists:hopitals,id',
            'status'          =>'required'
];
    }
}

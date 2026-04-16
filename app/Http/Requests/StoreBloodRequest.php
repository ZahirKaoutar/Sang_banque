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
            'center_id'       => 'required|exists:centers,id',
            'blood_group'     => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_needed' => 'required|integer|min:1',
            'priority'        => 'required|in:Normal,Urgent',
            'description'     => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'center_id.required' => 'Le centre est obligatoire.',
            'center_id.exists' => 'Le centre sélectionné n\'existe pas.',
            'blood_group.required' => 'Le groupe sanguin est obligatoire.',
            'blood_group.in' => 'Le groupe sanguin est invalide.',
            'quantity_needed.required' => 'La quantité est obligatoire.',
            'quantity_needed.integer' => 'La quantité doit être un nombre entier.',
            'priority.required' => 'La priorité est obligatoire.',
            'priority.in' => 'La priorité est invalide.',
        ];
    }
}

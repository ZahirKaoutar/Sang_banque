<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
       $userId = $this->route('id'); 

        return [
            'name'        => 'sometimes|string|min:3',
            'email'       => 'sometimes|email|unique:users,email,' . $userId,
            'phone'       => 'sometimes|unique:users,phone,' . $userId,
            'password'    => 'sometimes|string|min:4',
            'blood_group' => 'sometimes|string',
            'city'        => 'sometimes|string',
        ];
    }
}

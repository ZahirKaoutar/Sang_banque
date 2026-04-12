<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'city'     => 'required',
            'phone'    => 'required|unique:users,phone',
            'blood_group' => 'nullable|string',
        ];
    }
    public function messages(): array
{
    return [
        'name.required'     => 'Le nom est obligatoire.',
        'name.min'          => 'Le nom doit contenir au moins 3 caractères.',
        'email.required'    => 'L\'adresse email est indispensable.',
        'email.email'       => 'Veuillez entrer une adresse email valide.',
        'email.unique'      => 'Cet email est déjà utilisé par un autre compte.',
        'password.required' => 'Le mot de passe est requis.',
        'password.min'      => 'Le mot de passe doit faire au moins 4 caractères.',
        'city.required'     => 'La ville est obligatoire.',
        'phone.required'    => 'Le numéro de téléphone est obligatoire.',
        'phone.unique'      => 'Ce numéro de téléphone est déjà enregistré.',
    ];
}
}

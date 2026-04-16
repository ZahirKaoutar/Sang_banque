<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCentreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:4|confirmed',
            'center_name' => 'required|string|min:3',
            'address' => 'required|string',
            'city' => 'required|string',
            'license_number' => 'required|string|unique:centers,liscence_number',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'agent est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'phone.required' => 'Le téléphone est obligatoire.',
            'phone.unique' => 'Ce téléphone est déjà enregistré.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'center_name.required' => 'Le nom du centre est obligatoire.',
            'address.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'license_number.unique' => 'Ce numéro de licence existe déjà.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'document_type' => ['required', 'in:V,E,J,G'],
            'document_number' => ['required', 'numeric', 'unique:users,document_number'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'max:15',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[=*\-._]/',
                'regex:/^(?:(.)(?!\1\1))*$/'
            ],
        ];

        if (in_array($this->document_type, ['V', 'E'])) {
            $rules['document_number'][] = 'digits_between:6,8';
        } elseif (in_array($this->document_type, ['J', 'G'])) {
            $rules['document_number'][] = 'digits:9';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'La contraseña no cumple con los requisitos de seguridad.',
            'document_number.unique' => 'Este documento ya se encuentra registrado.',
            'document_number.numeric' => 'El documento solo debe contener números.',
            'document_number.digits_between' => 'La cédula debe tener entre 6 y 8 números.',
            'document_number.digits' => 'El RIF debe tener 9 números.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'La contraseña no cumple con los requisitos mínimos de seguridad estricta.',
        ];
    }
}

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
            'nombre'   => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'email'    => [
                'required', 
                'email:rfc,filter', 
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/', // Obliga formato valido x@x.com
                'regex:/^\S*$/', // Sin espacios en lo absoluto
                'unique:users,email'
            ],
            'tipo_documento' => ['required', 'in:V,E,J,G'],
            'documento_identidad' => [
                'required', 
                'numeric', 
                'digits_between:6,9', 
                'not_regex:/^(\d)\1+$/', // No permitir secuencias de un mismo número repetido (ej. 0000, 9999)
                'unique:users,documento_identidad'
            ],
            'telefono_prefijo' => ['nullable', 'string', 'in:0412,0414,0424,0416,0426,0212'],
            'telefono_numero'  => ['nullable', 'numeric', 'digits:7', 'not_regex:/^(\d)\1+$/'],
            'password' => [
                'required',
                'confirmed',
                'string',
                new \App\Rules\StrictPasswordRule
            ],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nombre.regex' => 'El nombre no debe contener números ni símbolos especiales.',
            'apellido.regex' => 'El apellido no debe contener números ni símbolos especiales.',
            'telefono_numero.digits' => 'El número de teléfono debe tener exactamente 7 dígitos numéricos.',
            'telefono_numero.numeric' => 'El número de teléfono solo debe contener números.',
            'telefono_numero.not_regex' => 'El número de teléfono es inválido (secuencia repetida).',
            'documento_identidad.unique' => 'Este documento ya se encuentra registrado.',
            'documento_identidad.numeric' => 'El documento solo debe contener números.',
            'documento_identidad.not_regex' => 'El documento de identidad es inválido (secuencia numérica repetida).',
            'documento_identidad.digits_between' => 'La cédula debe tener entre 6 y 8 números enteros.',
            'email.email' => 'Debes usar un formato de correo electrónico estructuralmente válido.',
            'email.regex' => 'El correo es inválido. Recuerda que no puede contener espacios, ni formatos incorrectos (ej. @.@).',
        ];
    }
}

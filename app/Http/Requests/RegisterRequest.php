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
                'regex:/^[a-zA-Z0-9._%+\-]+@(gmail\.com|hotmail\.com|yahoo\.com|yahoo\.es|outlook\.com|icloud\.com|live\.com|msn\.com|mac\.com|me\.com|aol\.com|protonmail\.com)$/i', // Obliga formato valido con proveedores reales
                'regex:/^\S*$/', // Sin espacios en lo absoluto
                'unique:users,email'
            ],
            'tipo_documento' => ['required', 'in:V,E,J,G'],
            'documento_identidad' => [
                'required', 
                'numeric', 
                'not_regex:/^(\d)\1+$/', // No permitir secuencias de un mismo número repetido (ej. 111111)
                'not_regex:/(123456|23456...|654321|987654)/', // Filtro basico de falsa secuencia
                'unique:users,documento_identidad'
            ],
            'telefono_prefijo' => ['nullable', 'string', 'in:0412,0414,0424,0416,0426,0212'],
            'telefono_numero'  => [
                'nullable', 
                'numeric', 
                'digits:7', 
                'not_regex:/^(\d)\1+$/',
                'not_regex:/(1234567|23456...|7654321|9876543)/'
            ],
            'password' => [
                'required',
                'confirmed',
                'string',
                new \App\Rules\StrictPasswordRule
            ],
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tipo = $this->tipo_documento;
            $doc = $this->documento_identidad;
            
            if ($doc) {
                if (in_array($tipo, ['V', 'E']) && (strlen($doc) < 6 || strlen($doc) > 8)) {
                    $validator->errors()->add('documento_identidad', 'La cédula venezolana debe tener entre 6 y 8 números.');
                }
                if (in_array($tipo, ['J', 'G']) && strlen($doc) != 9) {
                    $validator->errors()->add('documento_identidad', 'El RIF (J o G) debe tener exactamente 9 números numéricos.');
                }
            }
        });
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
            'email.regex' => 'El correo es inválido. Recuerda que no puede contener espacios y debe ser un proveedor válido (ej. @gmail.com, @hotmail.com, @yahoo.com).',
        ];
    }
}

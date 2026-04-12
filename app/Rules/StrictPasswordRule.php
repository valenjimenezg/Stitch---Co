<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class StrictPasswordRule implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Longitud
        $length = mb_strlen($value);
        if ($length < 8 || $length > 15) {
            $fail('La contraseña debe tener entre 8 y 15 caracteres.');
            return;
        }

        // 2. Prohibición de Espacios
        if (preg_match('/\s/', $value)) {
            $fail('No se permiten espacios en la contraseña.');
            return;
        }

        // 3. Composición Obligatoria
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Falta una mayúscula.');
            return;
        }
        if (!preg_match('/[a-z]/', $value)) {
            $fail('Falta una minúscula.');
            return;
        }
        if (!preg_match('/[0-9]/', $value)) {
            $fail('Falta un número.');
            return;
        }

        // 4. Caracteres Especiales (Solo letras permitidas, num y los exactos simbolos)
        // Esto prohíbe explícitamente ñ, Ñ, acentos, ; : , < > \ " ' & % $
        if (!preg_match('/^[a-zA-Z0-9*.\-_@#]+$/', $value)) {
            $fail('Caracteres denegados. Solo se permiten letras (sin ñ/acentos), números y los símbolos: * - _ . @ #');
            return;
        }

        // 5. Validación de Identidad
        $passLower = mb_strtolower($value);
        
        $nombre = isset($this->data['nombre']) ? mb_strtolower(trim($this->data['nombre'])) : '';
        $apellido = isset($this->data['apellido']) ? mb_strtolower(trim($this->data['apellido'])) : '';
        $documento = isset($this->data['documento_identidad']) ? trim($this->data['documento_identidad']) : '';
        
        // Asumiendo que tal vez guarden año de nacimiento en el request
        $añoNacimiento = isset($this->data['ano_nacimiento']) ? trim($this->data['ano_nacimiento']) : '';

        if ($nombre && str_contains($passLower, $nombre)) {
            $fail('Por seguridad, la contraseña no puede contener tu nombre.');
            return;
        }
        if ($apellido && str_contains($passLower, $apellido)) {
            $fail('Por seguridad, la contraseña no puede contener tu apellido.');
            return;
        }
        if ($documento && mb_strlen($documento) > 4 && str_contains($passLower, $documento)) {
            $fail('Por seguridad, la contraseña no puede contener tu cédula de identidad.');
            return;
        }
        if ($añoNacimiento && str_contains($passLower, $añoNacimiento)) {
            $fail('Por seguridad, la contraseña no puede contener tu año de nacimiento.');
            return;
        }

        // 6. Restricción de Secuencias obvias
        $secuenciasNumericas = [
            '012', '123', '234', '345', '456', '567', '678', '789',
            '987', '876', '765', '654', '543', '432', '321', '210'
        ];
        
        $secuenciasLetras = [
            'abc', 'bcd', 'cde', 'def', 'efg', 'fgh', 'ghi', 'hij', 'ijk', 'jkl', 'klm', 'lmn', 'mno', 'nop', 'opq', 'pqr', 'qrs', 'rst', 'stu', 'tuv', 'uvw', 'vwx', 'wxy', 'xyz',
            'zyx', 'yxw', 'xwv', 'wvu', 'vut', 'uts', 'tsr', 'srq', 'rqp', 'qpo', 'pon', 'onm', 'nml', 'mlk', 'lkj', 'kji', 'jih', 'ihg', 'hgf', 'gfe', 'fed', 'edc', 'dcb', 'cba'
        ];

        foreach ($secuenciasNumericas as $seq) {
            if (str_contains($passLower, $seq)) {
                $fail('No se permiten secuencias obvias de números (ej. 123 o 321).');
                return;
            }
        }

        foreach ($secuenciasLetras as $seq) {
            if (str_contains($passLower, $seq)) {
                $fail('No se permiten secuencias alfabéticas obvias (ej. abc o zyx).');
                return;
            }
        }
    }
}

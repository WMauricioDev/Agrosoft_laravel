<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CambiarPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'actual_password' => ['required', 'string'],
            'nueva_password' => ['required', 'string', 'min:8', 'confirmed'],
            'nueva_password_confirmation' => ['required', 'string'],

        ];
    }

    public function messages(): array
    {
        return [
            'actual_password.required' => 'La contraseña actual es obligatoria.',
            'nueva_password.required' => 'La nueva contraseña es obligatoria.',
            'nueva_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'nueva_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'nueva_password_confirmation.required' => 'Debe confirmar la nueva contraseña.',

            
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Errores de validación',
            'errors' => $validator->errors(),
        ], 422));
    }
}

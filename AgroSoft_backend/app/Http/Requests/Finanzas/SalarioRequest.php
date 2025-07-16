<?php

namespace App\Http\Requests\Finanzas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SalarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rol_id' => ['required', 'integer', 'exists:roles,id'],
            'fecha_de_implementacion' => ['required', 'date'],
            'valor_jornal' => ['required', 'regex:/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/'], // formato 40.000
        ];
    }

    public function messages(): array
    {
        return [
            'rol_id.required' => 'El rol es obligatorio.',
            'rol_id.exists' => 'El rol seleccionado no existe.',
            'fecha_de_implementacion.required' => 'La fecha de implementación es obligatoria.',
            'fecha_de_implementacion.date' => 'La fecha de implementación debe tener formato válido.',
            'valor_jornal.required' => 'El valor jornal es obligatorio.',
            'valor_jornal.regex' => 'El valor jornal debe tener el formato 40.000 (punto como separador de miles).',
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

<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TipoControlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
                'unique:tipo_controles,nombre'
            ],
            'descripcion' => [
                'required',
                'string'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.regex' => 'El nombre no debe contener números ni caracteres especiales.',
            'nombre.unique' => 'El nombre ya ha sido registrado.',
            'descripcion.required' => 'La descripción es requerida.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
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

<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateLoteRequest extends FormRequest
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
                'max:15',
                Rule::unique('lotes')->ignore($this->route('lote')->id),
            ],
            'descripcion' => ['nullable', 'string', 'max:65535'],
            'activo' => ['required', 'boolean'],
            'tam_x' => ['required', 'numeric'],
            'tam_y' => ['required', 'numeric'],
            'latitud' => ['required', 'numeric'],
            'longitud' => ['required', 'numeric'],
            'lote_id' => ['required', 'numeric'],

        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del lote es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede exceder los 15 caracteres.',
            'nombre.unique' => 'El nombre del lote ya está en uso.',

            'descripcion.string' => 'La descripción debe ser un texto válido.',

            'activo.required' => 'El estado activo es obligatorio.',
            'activo.boolean' => 'El valor de activo debe ser verdadero o falso.',

            'tam_x.required' => 'El tamaño X es obligatorio.',
            'tam_x.numeric' => 'El tamaño X debe ser un número.',

            'tam_y.required' => 'El tamaño Y es obligatorio.',
            'tam_y.numeric' => 'El tamaño Y debe ser un número.',

            'latitud.required' => 'La latitud es obligatoria.',
            'latitud.numeric' => 'La latitud debe ser un número.',

            'longitud.required' => 'La longitud es obligatoria.',
            'longitud.numeric' => 'La longitud debe ser un número.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}

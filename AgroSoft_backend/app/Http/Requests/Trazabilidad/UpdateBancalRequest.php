<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateBancalRequest extends FormRequest
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
                Rule::unique('bancals')->ignore($this->route('bancal')->id),
            ],
            'tam_x' => ['required', 'numeric'],
            'tam_y' => ['required', 'numeric'],
            'latitud' => ['required', 'numeric'],
            'longitud' => ['required', 'numeric'],
            'lote_id' => ['required']

        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del bancal es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede exceder los 15 caracteres.',
            'nombre.unique' => 'El nombre del bancal ya está en uso.',

            'tam_x.required' => 'El tamaño X es obligatorio.',
            'tam_x.numeric' => 'El tamaño X debe ser un número.',

            'tam_y.required' => 'El tamaño Y es obligatorio.',
            'tam_y.numeric' => 'El tamaño Y debe ser un número.',

            'latitud.required' => 'La latitud es obligatoria.',
            'latitud.numeric' => 'La latitud debe ser un número.',

            'longitud.required' => 'La longitud es obligatoria.',
            'longitud.numeric' => 'La longitud debe ser un número.',

            'lote_id.required' => 'El id del lote es requerido'

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}

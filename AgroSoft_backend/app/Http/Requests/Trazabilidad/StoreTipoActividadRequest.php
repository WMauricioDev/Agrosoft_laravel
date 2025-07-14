<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StoreTipoActividadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cambiar a true si quieres permitir la petición
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:tipo_actividades,nombre',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:65535',
            ]
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la actividad es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'nombre.unique' => 'El nombre de la actividad ya está en uso.',

            'descripcion.string' => 'La descripción debe ser un texto válido.',

        ];
    }
}

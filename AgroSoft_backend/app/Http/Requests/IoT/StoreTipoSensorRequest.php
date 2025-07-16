<?php

namespace App\Http\Requests\IoT;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTipoSensorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:50', 'unique:tipo_sensores,nombre'],
            'unidad_medida' => ['required', 'string', 'max:10'],
            'medida_minima' => ['required', 'numeric'],
            'medida_maxima' => ['required', 'numeric', 'gt:medida_minima'],
            'descripcion' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'nombre.unique' => 'El nombre ya está registrado.',
            'unidad_medida.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida.string' => 'La unidad de medida debe ser una cadena de texto.',
            'unidad_medida.max' => 'La unidad de medida no puede exceder los 10 caracteres.',
            'medida_minima.required' => 'La medida mínima es obligatoria.',
            'medida_minima.numeric' => 'La medida mínima debe ser un número.',
            'medida_maxima.required' => 'La medida máxima es obligatoria.',
            'medida_maxima.numeric' => 'La medida máxima debe ser un número.',
            'medida_maxima.gt' => 'La medida máxima debe ser mayor que la medida mínima.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
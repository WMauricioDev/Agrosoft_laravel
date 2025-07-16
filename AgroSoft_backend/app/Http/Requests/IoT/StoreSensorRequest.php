<?php

namespace App\Http\Requests\IoT;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSensorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'tipo_sensor_id' => ['required', 'exists:tipo_sensores,id'],
            'descripcion' => ['nullable', 'string'],
            'bancal_id' => ['nullable', 'exists:bancals,id'],
            'medida_minima' => ['nullable', 'numeric'],
            'medida_maxima' => ['nullable', 'numeric', 'gt:medida_minima'],
            'estado' => ['nullable', 'in:activo,inactivo'],
            'device_code' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'tipo_sensor_id.required' => 'El tipo de sensor es obligatorio.',
            'tipo_sensor_id.exists' => 'El tipo de sensor seleccionado no existe.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'bancal_id.exists' => 'El bancal seleccionado no existe.',
            'medida_minima.numeric' => 'La medida mínima debe ser un número.',
            'medida_maxima.numeric' => 'La medida máxima debe ser un número.',
            'medida_maxima.gt' => 'La medida máxima debe ser mayor que la medida mínima.',
            'estado.in' => 'El estado debe ser "activo" o "inactivo".',
            'device_code.string' => 'El código del dispositivo debe ser una cadena de texto.',
            'device_code.max' => 'El código del dispositivo no puede exceder los 100 caracteres.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
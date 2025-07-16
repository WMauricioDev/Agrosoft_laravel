<?php

namespace App\Http\Requests\IoT;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDatoMeteorologicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sensor_id' => ['nullable', 'exists:sensors,id'],
            'bancal_id' => ['nullable', 'exists:bancals,id'],
            'temperatura' => ['nullable', 'numeric'],
            'humedad_ambiente' => ['nullable', 'numeric'],
            'luminosidad' => ['nullable', 'numeric'],
            'lluvia' => ['nullable', 'numeric'],
            'velocidad_viento' => ['nullable', 'numeric'],
            'direccion_viento' => ['nullable', 'integer', 'between:0,360'],
            'humedad_suelo' => ['nullable', 'numeric'],
            'ph_suelo' => ['nullable', 'numeric', 'between:0,14'],
            'fecha_medicion' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'sensor_id.exists' => 'El sensor seleccionado no existe.',
            'bancal_id.exists' => 'El bancal seleccionado no existe.',
            'temperatura.numeric' => 'La temperatura debe ser un número.',
            'humedad_ambiente.numeric' => 'La humedad ambiente debe ser un número.',
            'luminosidad.numeric' => 'La luminosidad debe ser un número.',
            'lluvia.numeric' => 'La lluvia debe ser un número.',
            'velocidad_viento.numeric' => 'La velocidad del viento debe ser un número.',
            'direccion_viento.integer' => 'La dirección del viento debe ser un número entero.',
            'direccion_viento.between' => 'La dirección del viento debe estar entre 0 y 360 grados.',
            'humedad_suelo.numeric' => 'La humedad del suelo debe ser un número.',
            'ph_suelo.numeric' => 'El pH del suelo debe ser un número.',
            'ph_suelo.between' => 'El pH del suelo debe estar entre 0 y 14.',
            'fecha_medicion.date' => 'La fecha de medición debe ser una fecha válida.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ControlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'afeccion_id' => ['required', 'integer', 'exists:afecciones,id'],
            'tipo_control_id' => ['required', 'integer', 'exists:tipo_controles,id'],
            'producto_id' => ['required', 'integer', 'exists:insumos,id'],
            'descripcion' => ['required', 'string'],
            'fecha_control' => ['required', 'date'],
            'responsable_id' => ['required', 'integer', 'exists:users,id'],
            'efectividad' => ['nullable', 'numeric', 'between:0,100'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'afeccion_id.required' => 'El campo afección es obligatorio.',
            'afeccion_id.exists' => 'La afección seleccionada no existe.',
            'tipo_control_id.required' => 'El tipo de control es obligatorio.',
            'tipo_control_id.exists' => 'El tipo de control seleccionado no existe.',
            'producto_id.required' => 'El producto es obligatorio.',
            'producto_id.exists' => 'El producto seleccionado no existe.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'fecha_control.required' => 'La fecha de control es obligatoria.',
            'fecha_control.date' => 'La fecha de control debe ser una fecha válida.',
            'responsable_id.required' => 'El responsable es obligatorio.',
            'responsable_id.exists' => 'El responsable seleccionado no existe.',
            'efectividad.numeric' => 'La efectividad debe ser un número.',
            'efectividad.between' => 'La efectividad debe estar entre 0 y 100.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
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

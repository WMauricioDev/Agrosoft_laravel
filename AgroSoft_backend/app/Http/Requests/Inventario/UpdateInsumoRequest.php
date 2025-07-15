<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateInsumoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'unidad_medida_id' => ['nullable', 'exists:unidad_medidas,id'],
            'tipo_insumo_id' => ['nullable', 'exists:tipo_insumos,id'],
            'activo' => ['boolean'],
            'tipo_empacado' => ['nullable', 'string', 'max:100'],
            'fecha_caducidad' => ['nullable', 'date'],
            'precio_insumo' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',

            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',

            'unidad_medida_id.exists' => 'La unidad de medida seleccionada no existe.',

            'tipo_insumo_id.exists' => 'El tipo de insumo seleccionado no existe.',

            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',

            'tipo_empacado.string' => 'El tipo de empacado debe ser una cadena de texto.',
            'tipo_empacado.max' => 'El tipo de empacado no puede exceder los 100 caracteres.',

            'fecha_caducidad.date' => 'La fecha de caducidad debe ser una fecha válida.',

            'precio_insumo.required' => 'El precio del insumo es obligatorio.',
            'precio_insumo.numeric' => 'El precio del insumo debe ser un valor numérico.',
            'precio_insumo.min' => 'El precio del insumo no puede ser negativo.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}

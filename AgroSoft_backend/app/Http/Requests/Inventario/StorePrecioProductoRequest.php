<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePrecioProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cosecha_id' => ['nullable', 'exists:cosechas,id'],
            'unidad_medida_id' => ['nullable', 'exists:unidad_medidas,id'],
            'precio' => ['required', 'numeric', 'min:0'],
            'fecha_registro' => ['required', 'date'],
            'stock' => ['required', 'integer', 'min:0'],
            'fecha_caducidad' => ['nullable', 'date', 'after_or_equal:fecha_registro'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cosecha_id.exists' => 'La cosecha seleccionada no existe.',

            'unidad_medida_id.exists' => 'La unidad de medida seleccionada no existe.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'precio.min' => 'El precio no puede ser negativo.',

            'fecha_registro.required' => 'La fecha de registro es obligatoria.',
            'fecha_registro.date' => 'La fecha de registro debe ser una fecha válida.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',

            'fecha_caducidad.date' => 'La fecha de caducidad debe ser una fecha válida.',
            'fecha_caducidad.after_or_equal' => 'La fecha de caducidad debe ser igual o posterior a la fecha de registro.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
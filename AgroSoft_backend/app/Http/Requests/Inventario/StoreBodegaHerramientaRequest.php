<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBodegaHerramientaRequest extends FormRequest
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
            'bodega' => ['required', 'exists:bodegas,id'],
            'herramienta' => ['required', 'exists:herramientas,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'creador' => ['nullable', 'exists:users,id'],
            'cantidad_prestada' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'bodega.required' => 'El ID de la bodega es obligatorio.',
            'bodega.exists' => 'La bodega seleccionada no existe.',

            'herramienta.required' => 'El ID de la herramienta es obligatorio.',
            'herramienta.exists' => 'La herramienta seleccionada no existe.',

            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',

            'creador.exists' => 'El usuario creador seleccionado no existe.',

            'cantidad_prestada.integer' => 'La cantidad prestada debe ser un número entero.',
            'cantidad_prestada.min' => 'La cantidad prestada no puede ser negativa.',
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
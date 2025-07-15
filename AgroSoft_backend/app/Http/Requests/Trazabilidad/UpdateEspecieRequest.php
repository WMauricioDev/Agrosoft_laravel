<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class UpdateEspecieRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_especie_id' => 'required|exists:tipo_especies,id',
            'nombre' => 'required|string|max:30|unique:especies,nombre,' . $this->especie?->id,
            'descripcion' => 'required|string',
            'largo_crecimiento' => 'required|integer|min:0',
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
            'tipo_especie_id.required' => 'El tipo de especie es obligatorio.',
            'tipo_especie_id.exists' => 'El tipo de especie seleccionado no existe.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 30 caracteres.',
            'nombre.unique' => 'El nombre ya está registrado.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'largo_crecimiento.required' => 'El largo de crecimiento es obligatorio.',
            'largo_crecimiento.integer' => 'El largo de crecimiento debe ser un número entero.',
            'largo_crecimiento.min' => 'El largo de crecimiento no puede ser menor a 0.',
        ];
    }
}

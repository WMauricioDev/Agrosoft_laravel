<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StoreCultivoRequest extends FormRequest
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
            'especie_id' => 'required|exists:especies,id',
            'bancal_id' => 'required|exists:bancals,id',
            'nombre' => 'required|string|max:50|unique:cultivos,nombre',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'activo' => 'required|boolean',
            'fecha_siembra' => 'required|date',
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
            'especie_id.required' => 'La especie es obligatoria.',
            'especie_id.exists' => 'La especie seleccionada no existe.',

            'bancal_id.required' => 'El bancal es obligatorio.',
            'bancal_id.exists' => 'El bancal seleccionado no existe.',

            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'nombre.unique' => 'El nombre ya está registrado.',

            'unidad_medida_id.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida_id.exists' => 'La unidad de medida seleccionada no existe.',

            'activo.required' => 'El estado activo es obligatorio.',
            'activo.boolean' => 'El estado activo debe ser verdadero o falso.',

            'fecha_siembra.required' => 'La fecha de siembra es obligatoria.',
            'fecha_siembra.date' => 'La fecha de siembra debe ser una fecha válida.',
        ];
    }
}

<?php

namespace App\Http\Requests\Trazabilidad;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Trazabilidad\Tipo_Control; 

class UpdateTipoControlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

         $tipoControlId = $this->route('tipo_control');

    if (!Tipo_Control::find($tipoControlId)) {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Tipo de control no encontrado.',
        ], 404));
    }
        return [
            'nombre' => [
                'required',
                'string',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
                 Rule::unique('tipo_controles', 'nombre')->ignore($this->route('tipo_control')),
                
            ],
            'descripcion' => [
                'required',
                'string'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.regex' => 'El nombre no debe contener números ni caracteres especiales.',
            'nombre.unique' => 'El nombre ya ha sido registrado.',
            'descripcion.required' => 'La descripción es requerida.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
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

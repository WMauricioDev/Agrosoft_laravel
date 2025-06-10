<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInsumoRequest extends FormRequest
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
            'nombre'      => [
                'sometimes',
                'string',
                'max:255',
                'alpha_space',
            ],

            'descripcion' => [
                'sometimes',
                'string',
                'max:1000',
                'regex:/^[\pL0-9\.,;:\-\(\)\s]+$/u',
            ],

            'lote'        => [
                'sometimes',
                'integer',
                'min:1',
            ],
        ];
    }
  
    public function messages(): array
    { 
        return [

            'nombre.string'         => 'El nombre debe ser un texto válido.',
            'nombre.max'            => 'El nombre no puede exceder los 255 caracteres.',
            'nombre.alpha_space'    => 'El nombre solo puede contener letras y espacios.',

            'descripcion.string'    => 'La descripción debe ser un texto válido.',
            'descripcion.max'       => 'La descripción no puede exceder los 1000 caracteres.',
            'descripcion.regex'     => 'La descripción contiene caracteres no permitidos.',

            'lote.integer'          => 'El lote debe ser un número entero.',
            'lote.min'              => 'El lote debe ser al menos 1.',
        ];
    }
}

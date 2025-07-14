<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StoreBancalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cambiar a true si quieres permitir la petición
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
            'nombre' => [
                'required',
                'string',
                'max:15',
                'unique:bancals,nombre',
            ],
            'tam_x' => [
                'required',
                'numeric',
            ],
            'tam_y' => [
                'required',
                'numeric',
            ],
            'latitud' => [
                'required',
                'numeric',
            ],
            'longitud' => [
                'required',
                'numeric',
            ],
            'lote_id' => [
                'required',
            ],
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
            'nombre.required' => 'El nombre del bancal es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede exceder los 15 caracteres.',
            'nombre.unique' => 'El nombre del bancal ya está en uso.',
            
            'tam_x.required' => 'El tamaño X es obligatorio.',
            'tam_x.numeric' => 'El tamaño X debe ser un número.',

            'tam_y.required' => 'El tamaño Y es obligatorio.',
            'tam_y.numeric' => 'El tamaño Y debe ser un número.',

            'latitud.required' => 'La latitud es obligatoria.',
            'latitud.numeric' => 'La latitud debe ser un número.',

            'longitud.required' => 'La longitud es obligatoria.',
            'longitud.numeric' => 'La longitud debe ser un número.',

            'lote_id.required' => 'El id del lote es requerido.',

        ];
    }
}

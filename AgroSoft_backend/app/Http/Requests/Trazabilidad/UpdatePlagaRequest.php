<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Trazabilidad\Plaga;

class UpdatePlagaRequest extends FormRequest
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
        $plagaId = $this->route('plaga')->id;

        return [
            'nombre' => ['required', 'string', 'max:50', 'unique:plagas,nombre,' . $plagaId],
            'descripcion' => ['required', 'string'],
            'fk_tipo_plaga' => ['sometimes', 'exists:tipo_plagas,id'],
            'img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'nombre.unique' => 'El nombre ya está en uso por otra plaga.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',

            'fk_tipo_plaga.exists' => 'El tipo de plaga seleccionado no existe.',

            'img.image' => 'El archivo debe ser una imagen.',
            'img.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'img.max' => 'La imagen no puede exceder los 2MB.',
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
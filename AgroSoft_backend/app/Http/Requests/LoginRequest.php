<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'numero_documento'    => [
                'required',
                'numeric',
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
    
            'numero_documento.required'     =>     'El Numero de documento es obligatorio.',
            'numero_documento.string'       =>     'El Numero de documento debe ser un texto válido.',
            'numero_documento.numero_documento'        =>     'El Numero de documento debe tener un formato válido.',
            'numero_documento.max'          =>     'El Numero de documento no puede exceder los 20 caracteres.',
    
            'password.required'  =>     'La contraseña es obligatoria.',
            'password.numeric'    =>     'La contraseña debe ser un texto válido.',
            'password.min'       =>     'La contraseña debe tener al menos 6 caracteres.',
        ];

    }
}

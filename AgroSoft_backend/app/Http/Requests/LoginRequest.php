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
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:5'
            ],
        ];
    }

    public function messages()
    {
        return [
    
            'email.required'     =>     'El correo electrónico es obligatorio.',
            'email.string'       =>     'El correo electrónico debe ser un texto válido.',
            'email.email'        =>     'El correo electrónico debe tener un formato válido.',
            'email.max'          =>     'El correo electrónico no puede exceder los 255 caracteres.',
    
            'password.required'  =>     'La contraseña es obligatoria.',
            'password.string'    =>     'La contraseña debe ser un texto válido.',
            'password.min'       =>     'La contraseña debe tener al menos 5 caracteres.',
        ];

    }
}

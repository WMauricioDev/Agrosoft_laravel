<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SebastianBergmann\CodeUnit\FunctionUnit;

class RegisterRequest extends FormRequest
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
            'name'                  => [
                'required',
                'string',
                'max:255',
                'alpha_space',
            ],
            'email'                 => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'numero_documento'                 => [
                'required',
                'numeric',
                'digits_between:6,20', 
                'unique:users,numero_documento',
            ],
            'password'      =>      [
                'string',
                'nullable',

            ],

        ];
    }

    public function messages()
    {

        return [
            'name.required'                   => 'El nombre es obligatorio.',
            'name.string'                     => 'El nombre debe ser un texto válido.',
            'name.max'                        => 'El nombre no puede exceder los 255 caracteres.',
            'name.alpha_space'                => 'El nombre solo puede contener letras y espacios.',


            'email.required'                  => 'El email es obligatorio.',
            'email.email'                     => 'El email debe tener un formato válido.',
            'email.max'                       => 'El email no puede exceder los 255 caracteres.',
            'email.unique'                    => 'El correo ya existe en la base de datos.',

            'numero_documento.required'  => 'El numero de documento es obligatoria.',
            'numero_documento.min'       => 'El numero de documento debe tener mínimo 6 caracteres.',
            'numero_documento.max'       => 'El numero de documento debe tener maximo 20 caracteres.',
            'numero_documento.unique'    => 'El numero de documento ingresado ya esta en uso.',

           
            
        ];
    }
    
}

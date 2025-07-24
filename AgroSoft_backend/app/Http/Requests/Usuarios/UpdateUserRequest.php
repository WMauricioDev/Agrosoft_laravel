<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;
use SebastianBergmann\CodeUnit\FunctionUnit;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Illuminate\Validation\Rule;



class UpdateUserRequest extends FormRequest
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
     * 
     */
protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(response()->json([
        'success'=>false,
        'message'=>'Errores de validación',
        'errors'=>$validator->errors(),
    ],422));
}



    public function rules(): array
    {

        return [
            'nombre'                  => [
                'string',
                'max:255',
                'alpha_space',
            ],
            'apellido'                  => [
                'string',
                'max:255',
                'alpha_space',
            ],
            'email'                 => [
                'string',
                'email',
                'max:255',
                 Rule::unique('users', 'email')->ignore($this->route('user')), 
        ],
            'numero_documento'                 => [
                'numeric',
                'digits_between:6,20', 
                 Rule::unique('users', 'numero_documento')->ignore($this->route('user')), 
            ],
        
                'estado' => [
                'boolean',
            ],


        ];
    }

    public function messages()
    {

        return [
            'nombre.required'                   => 'El nombre es obligatorio.',
            'nombre.string'                     => 'El nombre debe ser un texto válido.',
            'nombre.max'                        => 'El nombre no puede exceder los 255 caracteres.',
            'nombre.alpha_space'                => 'El nombre solo puede contener letras y espacios.',

            'apellido.required'                   => 'El apellido es obligatorio.',
            'apellido.string'                     => 'El apellido debe ser un texto válido.',
            'apellido.max'                        => 'El apellido no puede exceder los 255 caracteres.',
            'apellido.alpha_space'                => 'El apellido solo puede contener letras y espacios.',


            'email.required'                  => 'El email es obligatorio.',
            'email.email'                     => 'El email debe tener un formato válido.',
            'email.max'                       => 'El email no puede exceder los 255 caracteres.',
            'email.unique'                    => 'El correo ya se encuentra en uso por otro usuario.',

            'numero_documento.required'  => 'El numero de documento es obligatoria.',
            'numero_documento.min'       => 'El numero de documento debe tener mínimo 6 caracteres.',
            'numero_documento.max'       => 'El numero de documento debe tener maximo 20 caracteres.',
            'numero_documento.unique'    => 'El numero de documento ingresado ya esta en uso.',

           
            
        ];
    }
    
}

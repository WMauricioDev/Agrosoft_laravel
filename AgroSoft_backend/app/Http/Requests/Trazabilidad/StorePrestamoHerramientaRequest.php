<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StorePrestamoHerramientaRequest extends FormRequest
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
            'actividad_id' => 'required|exists:actividades,id',
            'herramienta_id' => 'required|exists:herramientas,id',
            'bodega_herramienta_id' => 'nullable|exists:bodega_herramientas,id',
            'cantidad_entregada' => 'required|integer|min:1',
            'cantidad_devuelta' => 'required|integer|min:0',
            'entregada' => 'required|boolean',
            'devuelta' => 'required|boolean',
            'fecha_devolucion' => 'nullable|date',
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
            'actividad_id.required' => 'La actividad es obligatoria.',
            'actividad_id.exists' => 'La actividad seleccionada no existe.',
            'herramienta_id.required' => 'La herramienta es obligatoria.',
            'herramienta_id.exists' => 'La herramienta seleccionada no existe.',
            'bodega_herramienta_id.exists' => 'La bodega de herramientas seleccionada no existe.',
            'cantidad_entregada.required' => 'La cantidad entregada es obligatoria.',
            'cantidad_entregada.integer' => 'La cantidad entregada debe ser un número entero.',
            'cantidad_entregada.min' => 'La cantidad entregada no puede ser menor a 1.',
            'cantidad_devuelta.required' => 'La cantidad devuelta es obligatoria.',
            'cantidad_devuelta.integer' => 'La cantidad devuelta debe ser un número entero.',
            'cantidad_devuelta.min' => 'La cantidad devuelta no puede ser menor a 0.',
            'entregada.required' => 'El estado de entrega es obligatorio.',
            'entregada.boolean' => 'El estado de entrega debe ser verdadero o falso.',
            'devuelta.required' => 'El estado de devolución es obligatorio.',
            'devuelta.boolean' => 'El estado de devolución debe ser verdadero o falso.',
            'fecha_devolucion.date' => 'La fecha de devolución debe ser una fecha válida.',
        ];
    }
}

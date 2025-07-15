<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StorePrestamoInsumoRequest extends FormRequest
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
            'insumo_id' => 'required|exists:insumos,id',
            'cantidad_usada' => 'required|integer|min:0',
            'cantidad_devuelta' => 'required|integer|min:0',
            'fecha_devolucion' => 'nullable|date',
            'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
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
            'insumo_id.required' => 'El insumo es obligatorio.',
            'insumo_id.exists' => 'El insumo seleccionado no existe.',
            'cantidad_usada.required' => 'La cantidad usada es obligatoria.',
            'cantidad_usada.integer' => 'La cantidad usada debe ser un número entero.',
            'cantidad_usada.min' => 'La cantidad usada no puede ser menor a 0.',
            'cantidad_devuelta.required' => 'La cantidad devuelta es obligatoria.',
            'cantidad_devuelta.integer' => 'La cantidad devuelta debe ser un número entero.',
            'cantidad_devuelta.min' => 'La cantidad devuelta no puede ser menor a 0.',
            'fecha_devolucion.date' => 'La fecha de devolución debe ser una fecha válida.',
            'unidad_medida_id.exists' => 'La unidad de medida seleccionada no existe.',
        ];
    }
}

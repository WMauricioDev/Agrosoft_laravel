<?php

namespace App\Http\Requests\Trazabilidad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class UpdateActividadRequest extends FormRequest
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
            'tipo_actividad_id' => 'required|exists:tipo_actividades,id',
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'cultivo_id' => 'required|exists:cultivos,id',
            'estado' => ['required', Rule::in(['PENDIENTE', 'EN_PROCESO', 'COMPLETADA', 'CANCELADA'])],
            'prioridad' => ['required', Rule::in(['ALTA', 'MEDIA', 'BAJA'])],
            'instrucciones_adicionales' => 'nullable|string',
            'usuarios' => 'nullable|array',
            'usuarios.*' => 'exists:users,id',
            'insumos' => 'nullable|array',
            'insumos.*.insumo_id' => 'required|exists:insumos,id',
            'insumos.*.cantidad_usada' => 'required|integer|min:0',
            'herramientas' => 'nullable|array',
            'herramientas.*.herramienta_id' => 'required|exists:herramientas,id',
            'herramientas.*.cantidad_entregada' => 'required|integer|min:1',
            'herramientas.*.entregada' => 'nullable|boolean',
            'herramientas.*.devuelta' => 'nullable|boolean',
            'herramientas.*.fecha_devolucion' => 'nullable|date',
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
            'tipo_actividad_id.required' => 'El tipo de actividad es obligatorio.',
            'tipo_actividad_id.exists' => 'El tipo de actividad seleccionado no existe.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'cultivo_id.required' => 'El cultivo es obligatorio.',
            'cultivo_id.exists' => 'El cultivo seleccionado no existe.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de: PENDIENTE, EN_PROCESO, COMPLETADA, CANCELADA.',
            'prioridad.required' => 'La prioridad es obligatoria.',
            'prioridad.in' => 'La prioridad debe ser una de: ALTA, MEDIA, BAJA.',
            'instrucciones_adicionales.string' => 'Las instrucciones adicionales deben ser una cadena de texto.',
            'usuarios.array' => 'Los usuarios deben proporcionarse como un arreglo.',
            'usuarios.*.exists' => 'Uno o más usuarios seleccionados no existen.',
            'insumos.array' => 'Los insumos deben proporcionarse como un arreglo.',
            'insumos.*.insumo_id.required' => 'El ID del insumo es obligatorio.',
            'insumos.*.insumo_id.exists' => 'El insumo seleccionado no existe.',
            'insumos.*.cantidad_usada.required' => 'La cantidad usada del insumo es obligatoria.',
            'insumos.*.cantidad_usada.integer' => 'La cantidad usada debe ser un número entero.',
            'insumos.*.cantidad_usada.min' => 'La cantidad usada no puede ser menor a 0.',
            'herramientas.array' => 'Las herramientas deben proporcionarse como un arreglo.',
            'herramientas.*.herramienta_id.required' => 'El ID de la herramienta es obligatorio.',
            'herramientas.*.herramienta_id.exists' => 'La herramienta seleccionada no existe.',
            'herramientas.*.cantidad_entregada.required' => 'La cantidad entregada de la herramienta es obligatoria.',
            'herramientas.*.cantidad_entregada.integer' => 'La cantidad entregada debe ser un número entero.',
            'herramientas.*.cantidad_entregada.min' => 'La cantidad entregada no puede ser menor a 1.',
            'herramientas.*.entregada.boolean' => 'El estado de entrega debe ser verdadero o falso.',
            'herramientas.*.devuelta.boolean' => 'El estado de devolución debe ser verdadero o falso.',
            'herramientas.*.fecha_devolucion.date' => 'La fecha de devolución debe ser una fecha válida.',
        ];
    }
}

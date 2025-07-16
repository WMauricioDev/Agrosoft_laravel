<?php

namespace App\Http\Requests\Finanzas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StorePagoRequest extends FormRequest
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
            'usuario_id' => 'required|exists:users,id',
            'salario_id' => 'required|exists:salarios,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'horas_trabajadas' => 'nullable|numeric|min:0',
            'jornales' => 'nullable|numeric|min:0',
            'total_pago' => 'nullable|numeric|min:0',
            'actividades' => 'nullable|array',
            'actividades.*' => 'exists:actividades,id',
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
            'usuario_id.required' => 'El ID del usuario es obligatorio.',
            'usuario_id.exists' => 'El usuario seleccionado no existe.',
            'salario_id.required' => 'El ID del salario es obligatorio.',
            'salario_id.exists' => 'El salario seleccionado no existe.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'horas_trabajadas.numeric' => 'Las horas trabajadas deben ser un número.',
            'horas_trabajadas.min' => 'Las horas trabajadas no pueden ser menores a 0.',
            'jornales.numeric' => 'Los jornales deben ser un número.',
            'jornales.min' => 'Los jornales no pueden ser menores a 0.',
            'total_pago.numeric' => 'El total del pago debe ser un número.',
            'total_pago.min' => 'El total del pago no puede ser menor a 0.',
            'actividades.array' => 'Las actividades deben proporcionarse como un arreglo.',
            'actividades.*.exists' => 'Una o más actividades seleccionadas no existen.',
        ];
    }
}

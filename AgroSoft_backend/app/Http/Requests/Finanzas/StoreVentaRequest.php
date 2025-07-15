<?php

namespace App\Http\Requests\Finanzas;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date'],
            'monto_entregado' => ['required', 'numeric', 'min:0'],
            'cambio' => ['required', 'numeric', 'min:0'],

            'detalles' => ['required', 'array', 'min:1'],
            'detalles.*.producto' => ['required', 'integer', 'exists:precio_productos,id'], // ajusta el nombre real de la tabla si es necesario
            'detalles.*.cantidad' => ['required', 'numeric', 'min:0.01'],
            'detalles.*.unidad_medidas' => ['required', 'integer', 'exists:unidad_medidas,id'], // ajusta el nombre real
            'detalles.*.total' => ['required', 'numeric', 'min:0'],
            // 'detalles.*.precio_unitario' => ['nullable', 'numeric', 'min:0'], // si decides validar el precio_unitario
        ];
    }
}

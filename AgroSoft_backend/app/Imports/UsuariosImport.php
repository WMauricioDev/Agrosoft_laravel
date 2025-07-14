<?php

namespace App\Imports;

use App\Models\Usuarios\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class UsuariosImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        $validator = Validator::make($data, [
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'numero_documento'  => 'required|numeric|unique:users,numero_documento',
        ]);

        if ($validator->fails()) {
            // Podrías registrar los errores en un log o archivo si quieres
            return;
        }

        // Generar contraseña si no viene
        if (empty($data['password'])) {
            $primeraLetra = strtolower(substr($data['nombre'], 0, 1));
            $generatedPassword = $primeraLetra . $data['numero_documento'];
            $data['password'] = $generatedPassword;
        }

        $data['password'] = Hash::make($data['password']);
        $data['email'] = $data['email'] ?? 'sin-email-' . uniqid() . '@example.com';
        $data['rol_id'] = $data['rol_id'] ?? 1;
        $data['estado'] = $data['estado'] ?? true;

        try {
            User::create($data);
        } catch (\Exception $e) {
            // Puedes loggear el error si quieres ver qué falló
            \Log::error('Error al importar usuario: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\Request;
use App\Imports\UsuariosImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class ImportUsuarioController extends Controller
{
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new UsuariosImport, $request->file('archivo'));

            return response()->json([
                'success' => true,
                'message' => 'Usuarios importados correctamente'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al importar: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

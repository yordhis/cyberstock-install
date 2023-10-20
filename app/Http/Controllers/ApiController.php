<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\{
    Helpers,
    Proveedore
};

class ApiController extends Controller
{

    public function getProveedor($idProveedor)
    {
        try {
            // return "hola";
            $proveedor = Proveedore::where(['codigo'=> $idProveedor])->get();

            if (count($proveedor)) {
                return response()->json([
                    "mensaje" => "Busqueda Exitosa",
                    "data" => $proveedor[0],
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "mensaje" => "No hay Resultados",
                    "data" => [],
                    "estatus" => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error en la API al retornar los datos del Proveedor en el método getProveedor,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

}

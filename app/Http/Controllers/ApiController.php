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
                    "data" => $proveedor,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "mensaje" => "No hay Resultados",
                    "data" => [],
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error en la API al retornar los datos del Proveedor en el método getProveedor,");
            return response()->json([
                "mensaje" => $errorInfo ,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

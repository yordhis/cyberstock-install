<?php

namespace App\Http\Controllers;

use App\Models\{
    Carrito,
    DataDev,
    Factura,
    Inventario,
    Producto
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ReporteController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }

    public function index(){
        $menuSuperior = $this->data->menuSuperior;
        $pathname = FacadesRequest::path();
        return view('admin.reportes.index', compact('menuSuperior', 'pathname'));
    }


    /** API REST FULL */

    public function storeReportes(Request $request){
        $reporte = [];

        switch ($request->tipo) {
            case 'dia':
                $resultados = Factura::whereDate('fecha', $request->rango['inicio'] )->get();
                return response()->json([
                    "mensaje" => "Consulta por fecha personalizada exitosa.",
                    "data" => $resultados,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
                break;

            case 'mensual':
                 $resultados = Factura::whereYear('fecha', explode('-', $request->rango['inicio'])[0] )
                ->whereMonth('fecha', '=', explode('-', $request->rango['inicio'])[1] ) // inicio mes
                ->get();
                return response()->json([
                    "mensaje" => "Consulta de reporte mensual exitosa.",
                    "data" => $resultados,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
                break;

            case 'personalizado':
            
                $resultados = Factura::whereDate('fecha', '>=',$request->rango['inicio'] )
                ->whereDate('fecha', '<=', $request->rango['fin'] )
                ->get();

                foreach ($resultados as $key => $factura) {
                    $carrito = Carrito::where('codigo', $factura->codigo)->get();
                    foreach ($carrito as $key => $producto) {
                        $producto['costoProveedor'] = Inventario::select('costo')->find($producto->id)->costo;
                        array_push($reporte, $producto);
                    }
                }
              
                break;
            
            default:
                    $reporte = [];
                break;
        }

        /** RESPUESTA */
        return response()->json([
            "mensaje" => "Consulta por fecha personalizada exitosa.",
            "data" => $reporte,
            "estatus" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

}

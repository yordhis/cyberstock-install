<?php

namespace App\Http\Controllers;

use App\Models\{
    Carrito,
    DataDev,
    Factura,
    Inventario,
    Po,
    Producto
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
        return view('admin.reportes.index', compact('menuSuperior'));
    }


    /** API REST FULL */

    public function storeReportes(Request $request){
        $reporte = [];

        switch ($request->tipo) {

            case 'reporteDelDia':
                $reporte = Factura::whereDate('fecha', '=', explode('T', $request->rango['inicio'])[0] )
                ->where('concepto', '=', 'VENTA')
                ->get();
                
                foreach ($reporte as $key => $factura) {
                    $totalArticulos = Carrito::select('cantidad')
                    ->where("codigo", $factura->codigo)
                    ->sum('cantidad');
                    $factura['cantidad_articulos'] = $totalArticulos;
                }
            
                break;
            case 'reporteDelDiaDetallado':
                    $resultados = Factura::whereDate('fecha', '=', explode('T', $request->rango['inicio'])[0] )
                    ->where('concepto', "=", "VENTA")
                    ->get();
                    foreach ($resultados as $key => $factura) {
                        $carrito = Carrito::where('codigo', $factura->codigo)->get();
                        foreach ($carrito as $key => $producto) {
                            $costo = Inventario::select('costo')->where('codigo', $producto->codigo_producto)->get();
                            $producto['costoProveedor'] = count($costo) 
                                                        ? $costo[0]['costo']
                                                        : 0;
                            $producto['iva'] = $factura->iva;
                            array_push($reporte, $producto);
                        }
                    }
                break;

            case 'storeReportes':
                    $reporte = Factura::whereDate('fecha', '>=',$request->rango['inicio'] )
                    ->whereDate('fecha', '<=', $request->rango['fin'] )
                    ->where('concepto', "=", "VENTA")
                    ->get();

                    foreach ($reporte as $key => $factura) {
                        $totalArticulos = Carrito::select('cantidad')
                        ->where("codigo", $factura->codigo)
                        ->sum('cantidad');
                        $factura['cantidad_articulos'] = $totalArticulos;
                    }
                break;

            case 'storeReportesDetallado':
                    $resultados = Factura::whereDate('fecha', '>=',$request->rango['inicio'] )
                    ->whereDate('fecha', '<=', $request->rango['fin'] )
                    ->where('concepto', "=", "VENTA")
                    ->get();

                    foreach ($resultados as $key => $factura) {
                        $carrito = Carrito::where('codigo', $factura->codigo)->get();
                        foreach ($carrito as $key => $producto) {
                            $costo = Inventario::select('costo')->where('codigo', $producto->codigo_producto)->get();
                            $producto['costoProveedor'] = count($costo) 
                                                        ? $costo[0]['costo']
                                                        : 0;
                            $producto['iva'] = $factura->iva;
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
            "mensaje" => "Datos de venta optenidos exitosamente. ->" . $request->tipo,
            "data" => $reporte,
            "estatus" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\{
    CarritoInventario,
    DataDev,
    Factura,
    FacturaInventario,
    Helpers,
    Pago,
    Proveedore
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PagarController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
   
        
            $menuSuperior = $this->data->menuSuperior;
    
            $pagar = FacturaInventario::where([
                "tipo" => 'ENTRADA',
            ])->orderBy('codigo_factura', 'desc')->get();
            $pagar_depurado = [];
            foreach ($pagar as $key => $value) {
                if($value->concepto != "DEVOLUCION"){

                    $value['abonos'] = Pago::where('codigo_factura', $value->codigo_factura)->get();
                    if( $value->concepto == "COMPRA" && count($value['abonos']) == 0 ) continue;

                    $value['proveedor']= Proveedore::where('codigo', $value->identificacion)->get();
                    $value['carrito'] = CarritoInventario::where('codigo', $value->codigo)->get();
                    foreach($value['carrito'] as $cantidade){
                        $value['totalArticulos'] += $cantidade->cantidad; 
                    }
                    $total_abono = 0;
                    foreach ($value['abonos'] as $key => $abono) {
                        $abono->fecha = date_format(date_create($abono->fecha), 'd-m-Y');
                        $total_abono = $total_abono + $abono->monto;
                    }
    
                    $value['total_abono'] = $total_abono;
                    array_push($pagar_depurado, $value);
                }
            }
            $pagar = $pagar_depurado;
            return view('admin.pagar.lista', compact('pagar', 'menuSuperior'));
        
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
   
        $dataValidada = $request->validate([
            "codigo_factura" => "required",
            "tipo_factura" => "required",
            "metodo" => "required",
            "monto" => "numeric | min:1 | required ",
            "fecha" => "required",
        ]);

        /** Registrar el pago del deudor */
        Pago::create( $dataValidada );

        /** Obtenemos todos los pagos */
        $todosLosPagos = Pago::where([
            "codigo_factura" => $request->codigo_factura,
            "tipo_factura" => $request->tipo_factura,
        ])->get();

        /** Calculamos el total de abonas de la factura */
        $total_abono = 0;
        foreach ($todosLosPagos as $key => $abono) {
            $total_abono = $total_abono + $abono->monto;
        }

        /** Consultamos la factura en proceso de pago */
        $facturaInventario = FacturaInventario::where('codigo_factura', $request->codigo_factura)->get();
       

        /** Validamos si los abonos cumplen con el total de la factura acreditada para cambiar el estatus a pagada (VENTA) */
        if($total_abono >= $facturaInventario[0]->total){

            $estatus = $facturaInventario[0]->update([
                "observacion" => $request->observacion,
                "concepto" => "COMPRA"
            ]);

            $mensaje = $estatus ? "¡Pago acreditado!, La factura está solvente.": "No se registro el pago";

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(request(), Response::HTTP_OK,"Acción de Eliminar facturar de inventario (entra/salida), código de factura: ({$facturaInventario[0]->codigo_factura})");

            return redirect()->route('admin.cuentas.por.pagar.index', [
                "mensaje" => $mensaje,
                "estatus" => Response::HTTP_OK
            ]);

       }else{
       
        $facturaInventario[0]->update([ "concepto" => "CREDITO" ]);
        
        return redirect()->route('admin.cuentas.por.pagar.index', [
            "mensaje" => "¡Pago acreditado!, Factura aun pendiente.",
            "estatus" => Response::HTTP_OK
        ]);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** Obtener los datos del pago */
        $abono = Pago::find($id);

        /** Obtener la factura */
        $facturaInventario = FacturaInventario::where('codigo_factura', $abono->codigo_factura)->get();
      

        /** Eliminamos el pago */
        Pago::where('id', $id)->delete();

        /** Calcular los abono  */
        $abonos = Pago::where('codigo_factura', $abono->codigo_factura)->get();
        $total_abono = 0;
        foreach ($abonos as $key => $abono) {
            $total_abono = $total_abono + $abono->monto;
        }

        if($total_abono >= $facturaInventario[0]->total){
       
            $facturaInventario[0]->update([ "concepto" => "COMPRA" ]);
        }else{
            $facturaInventario[0]->update([ "concepto" => "CREDITO" ]);
        }

        return redirect()->route('admin.cuentas.por.pagar.index', [
            "mensaje" => "¡Pago eliminado!",
            "estatus" => Response::HTTP_OK
        ]);
    }
}

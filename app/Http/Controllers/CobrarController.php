<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoInventario;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\Factura;
use App\Models\FacturaInventario;
use App\Models\Helpers;
use App\Models\Pago;
use App\Models\Po;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as FacadesRequest;

class CobrarController extends Controller
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
    
            $pathname = FacadesRequest::path();
            $menuSuperior = $this->data->menuSuperior;
    
            $cobrar = FacturaInventario::where([
                "tipo" => 'SALIDA'
            ])->get();

            foreach ($cobrar as $key => $value) {
                $value['cliente']= Cliente::where('identificacion', $value->identificacion)->get();
                $value['carrito'] = CarritoInventario::where('codigo', $value->codigo)->get();
                $value['abonos'] = Pago::where('codigo_factura', $value->codigo_factura)->get();
                
                foreach($value['carrito'] as $cantidade){
                    $value['totalArticulos'] += $cantidade->cantidad; 
                }
                
                $total_abono = 0;
                foreach ($value['abonos'] as $key => $abono) {
                    $total_abono = $total_abono + $abono->monto;
                }

                $value['total_abono'] = $total_abono;
            }

       
            
            // return $cobrar;
            return view('admin.cobrar.lista', compact('cobrar', 'menuSuperior', 'pathname'));
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
        $factura = Factura::where('codigo', $facturaInventario[0]->codigo_factura)->get();

        /** Validamos si los abonos cumplen con el total de la factura acreditada para cambiar el estatus a pagada (VENTA) */
        if($total_abono >= $facturaInventario[0]->total){

            $estatus = $factura[0]->update(["concepto" => "VENTA"]);
            $estatus = $facturaInventario[0]->update([
                "observacion" => $request->observacion,
                "concepto" => "VENTA"
            ]);

            $mensaje = $estatus ? "¡Pago acreditado!, La factura está solvente.": "No se registro el pago";

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(request(), Response::HTTP_OK,"Acción de Eliminar facturar de inventario (entra/salida), código de factura: ({$facturaInventario[0]->codigo_factura})");

            return redirect()->route('admin.cuentas.por.cobrar.index', [
                "mensaje" => $mensaje,
                "estatus" => Response::HTTP_OK
            ]);

       }else{
        $factura[0]->update(["concepto" => "CREDITO"]);
        $facturaInventario[0]->update([ "concepto" => "CREDITO" ]);
        
        return redirect()->route('admin.cuentas.por.cobrar.index', [
            "mensaje" => "¡Pago acreditado!, Factura aun pendiente.",
            "estatus" => Response::HTTP_OK
        ]);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $facturas = FacturaInventario::where('codigo', $id)->get();
           
            if(count($facturas)){
                foreach ($facturas as $key => $factura) {
                    $factura->carrito = CarritoInventario::where('codigo', $factura->codigo)->get();
                    $contador = 0;
                    foreach ($factura->carrito as $key => $producto) {
                        $contador += $producto->cantidad;
                    }
                    $factura->totalArticulos = $contador;

                }

                $factura = $facturas[0];
              
            }else{
                $mensaje = "El código de la factura no esta registrado, verifique el codigo.";
                $estatus = 404;
                return redirect()->route('admin.cuentas.por.cobrar.index', compact('mensaje', 'estatus'));
            }
          
            $pos = count(Po::all()) ? Po::all()[0]: [];
            // $pathname = FacadesRequest::path();
            // $pathname = explode('/', $pathname)[0] . '/ver';
     
            return view( 'admin.cobrar.ver', compact( 'factura', 'pos', 'menuSuperior' ) );
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
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
        $factura = Factura::where('codigo', $abono->codigo_factura)->get();

        /** Eliminamos el pago */
        Pago::where('id', $id)->delete();

        /** Calcular los abono  */
        $abonos = Pago::where('codigo_factura', $abono->codigo_factura)->get();
        $total_abono = 0;
        foreach ($abonos as $key => $abono) {
            $total_abono = $total_abono + $abono->monto;
        }

        if($total_abono >= $facturaInventario[0]->total){
            $factura[0]->update(["concepto" => "VENTA"]);
            $facturaInventario[0]->update([ "concepto" => "VENTA" ]);
        }else{
            $factura[0]->update(["concepto" => "CREDITO"]);
            $facturaInventario[0]->update([ "concepto" => "CREDITO" ]);
        }

        return redirect()->route('admin.cuentas.por.cobrar.index', [
            "mensaje" => "¡Pago eliminado!",
            "estatus" => Response::HTTP_OK
        ]);
    }
}

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
            ])->orderBy('codigo_factura', 'desc')->get();

            $cobrar_depurado = [];
            foreach ($cobrar as $key => $value) {
                if($value->concepto != "CONSUMO"){

                    $value['abonos'] = Pago::where('codigo_factura', $value->codigo_factura)->get();
                    if( $value->concepto == "VENTA" && count($value['abonos']) == 0 ) continue;
                    $value['cliente']= Cliente::where('identificacion', $value->identificacion)->get();
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
                    array_push($cobrar_depurado, $value);
                }
            }

       
            
            $cobrar = $cobrar_depurado;
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

        /** Consultamos la factura en proceso de pago */
        $facturaInventario = FacturaInventario::where('codigo_factura', $request->codigo_factura)->get();
        $factura = Factura::where('codigo', $facturaInventario[0]->codigo_factura)->get();

          /** Calculamos el total de abonas de la factura */
          $total_abono = 0;
          $metodosDePagos = [];
          foreach ($todosLosPagos as $key => $abono) {
              $total_abono = $total_abono + $abono->monto;
              if($abono->metodo != "DIVISAS"){
                  array_push($metodosDePagos, ["id"=>$key, "tipoDePago"=>$abono->metodo, "montoDelPago"=>$abono->monto * $facturaInventario[0]->tasa]);
              }else{
                  array_push($metodosDePagos, ["id"=>$key, "tipoDePago"=>$abono->metodo, "montoDelPago"=>$abono->monto]);
              }
          }

        /** Validamos si los abonos cumplen con el total de la factura acreditada para cambiar el estatus a pagada (VENTA) */
        if($total_abono >= $facturaInventario[0]->total){
            // [{"id":"1","tipoDePago":"DIVISAS","montoDelPago":10.8228}]
            /** Factura normal */
            $estatus = $factura[0]->update([
                "concepto" => "VENTA",
                "metodos" => json_encode($metodosDePagos)
            ]);

            /** Factura inventario */
            $estatus = $facturaInventario[0]->update([
                "observacion" => $request->observacion,
                "concepto" => "VENTA",
                "metodos" => json_encode($metodosDePagos)
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
            $factura = FacturaInventario::findOrFail($id);
            $factura->cliente = Cliente::where('identificacion', $factura->identificacion)->get();
            $factura->carrito = CarritoInventario::where('codigo_factura', $factura->codigo_factura)->get();
            $contador = 0;
            foreach ($factura->carrito as $key => $producto) {
                $contador += $producto->cantidad;
            }
            $factura->tasa = 1;
            $factura->totalArticulos = $contador;            
            $pos = count(Po::all()) ? Po::all()[0]: [];
        
            return view( 'admin.cobrar.ver', compact( 'factura', 'pos', 'menuSuperior' ) );
        } catch (\Throwable $th) {
            $mensaje = "El código de la factura no esta registrado, verifique el codigo.";
            $estatus = 404;
            return redirect()->route('admin.cuentas.por.cobrar.index', compact('mensaje', 'estatus'));
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

<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoInventario;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\Factura;
use App\Models\FacturaInventario;
use App\Models\Helpers;
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
                "tipo" => 'SALIDA',
                "concepto" => 'CREDITO',
            ])->paginate(10);
            foreach ($cobrar as $key => $value) {
                $value['cliente']= Cliente::where('identificacion', $value->identificacion)->get();
                $value['carrito'] = CarritoInventario::where('codigo', $value->codigo)->get();
                $contador = 0;
                foreach($value['carrito'] as $cantidade){
                    $value['totalArticulos'] += $cantidade->cantidad; 
                }
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
       $facturaInventario = FacturaInventario::where('codigo', $request->codigo)->get();
       $factura = Factura::where('codigo', $request->codigo)->get();
       if($factura){

        $estatus = $factura[0]->update(["concepto" => "VENTA"]);
        $estatus = $facturaInventario[0]->update([
            "observacion" => $request->observacion,
            "concepto" => "VENTA"
        ]);

        $mensaje = $estatus ? "El pago de la factura se porceso correctamente!": "No se registro el pago";

        /** registramos movimiento al usuario */
        Helpers::registrarMovimientoDeUsuario(request(), Response::HTTP_OK,"Acción de Eliminar facturar de inventario (entra/salida), código de factura: ({$facturaInventario[0]->codigo_factura})");

        return redirect()->route('admin.cuentas.por.cobrar.index', [
            "mensaje" => $mensaje,
            "estatus" => Response::HTTP_OK
        ]);
       }else{
        return redirect()->route('admin.cuentas.por.cobrar.index', [
            "mensaje" => "El código de la factura no esta registrado.",
            "estatus" => Response::HTTP_NOT_FOUND
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
            $facturas = FacturaInventario::where('codigo_factura', $id)->get();
           
            if(count($facturas)){
                foreach ($facturas as $key => $factura) {
                    $factura->carrito = CarritoInventario::where('codigo_factura', $factura->codigo)->get();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

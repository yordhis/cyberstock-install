<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Models\Carrito;
use App\Models\CarritoInventario;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\FacturaInventario;
use App\Models\Helpers;
use App\Models\Inventario;
use App\Models\Pago;
use App\Models\Po;
use App\Models\Proveedore;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class FacturaController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }

    /**
     * DESPLIEGA LA LISTA DE FACTURAS
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $pathname = Request::path();
            $facturas = Factura::orderBy('codigo', 'DESC')->get();
           
            if(count($facturas)){
                foreach ($facturas as $key => $factura) {
                    $factura->carrito = Carrito::where('codigo', $factura->codigo)->get();
                    // $factura->total_articulos = Carrito::where('codigo', $factura->codigo)->count();
                }
            }
           
            // $pos = count(Po::all()) ? Po::all()[0]: [];
     
            return view('admin.facturas.index', compact( 'facturas','menuSuperior', 'pathname'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /**
     * DESPLIEGA LA VISTA DE LA FACTURA DE MANERA ESPESIFICA
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show($id, $moneda="Bs")
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $utilidades = $this->data->utilidades;
            $factura = Factura::find($id);
            if($moneda == "$") $factura->tasa = 1;
            if($factura){
                    $factura->carrito = Carrito::where('codigo', $factura->codigo)->get();
                    $factura->cliente = Cliente::where('identificacion', $factura->identificacion)->get();
                    $contador = 0;
                    foreach ($factura->carrito as $key => $producto) {
                        $contador += $producto->cantidad;
                    }
                    $factura->totalArticulos = $contador;
                    $factura['hora']  =  date_format(date_create(explode('T', $factura->fecha)[1]), 'h:i:sa');               
                    $factura['fecha']  =  date_format(date_create(explode('T', $factura->fecha)[0]), 'd-m-Y');   
            }else{
                $mensaje = "El código de la factura no esta registrado, verifique el codigo.";
                $estatus = 404;
                return redirect()->route('admin.facturas.index', compact('mensaje', 'estatus'));
            }
          
            
            $pos = count(Po::all()) ? Po::all()[0]: [];
            $pathname = Request::path();
            $pathname = explode('/', $pathname)[0] . '/ver';
           
            return view( 'admin.facturas.ver', compact( 'factura', 'moneda', 'pos', 'utilidades', 'menuSuperior' ) );
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        try {
            Pago::where('codigo_factura', $factura->codigo)->delete();
            FacturaInventario::where('codigo_factura', $factura->codigo)->delete();
            CarritoInventario::where('codigo_factura', $factura->codigo)->delete();
            Carrito::where('codigo', $factura->codigo)->delete();
            $factura->delete();

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(request(), Response::HTTP_OK,"Acción de eliminar factura ({$factura->codigo})");

            return redirect()->route('admin.facturas.index',[
                "mensaje" => "Factura eliminada correctamente",
                "estatus" => Response::HTTP_OK
            ]);
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar ELIMINAR factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }



    /** API REST FULL */
    /** 
     * OBTIENE EL ULTIMO CODIGO DE FACTUAS O LA TABLA DESEADA
     * @param tabla string
     * @return \Illuminate\Http\Response string (codigo) 
     */
    public function getCodigoFactura($tabla){
        try {
            $codigo = Helpers::getCodigo($tabla);
            return response()->json([
                "mensaje" => "Consulta de codigo de factura exitoso",
                "estatus" => Response::HTTP_OK,
                "data" => $codigo
            ], Response::HTTP_OK);
            
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error en la API al retornar el codigo de la factura,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** 
     * OBTIENE TODOS LOS DATOS DE LA FACTURA POR MEDIO DEL CODIGO
     * @param codigoFactura - string
     * @return \Illuminate\Http\Response object (factura) 
     */
    public function getFactura($codigoFactura){
        try {
            $factura = Factura::where('codigo', $codigoFactura)->get();
            if(count($factura)){
                $totalArticulos=0;
                $factura = $factura[0];
                $carritos = Carrito::where('codigo', $factura->codigo)->get();
                foreach ($carritos as $key => $producto) {
                    $producto['stock'] = Inventario::select('cantidad')->where('codigo',$producto->codigo_producto)->get()[0]->cantidad;
                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                }

                // $carritos = Carrito::where('codigo', $factura->codigo)->get();
                $cliente = Cliente::where('identificacion', $factura->identificacion)->get();
                if(count($cliente)) $factura['cliente'] = $cliente[0];
                else $factura['cliente'] = ["nombre" => "El Cliente fue eliminado"];
                $factura['carrito'] = $carritos; 
                $factura['pos'] = Po::all()[0];
                $factura['hora']  =  date_format(date_create(explode('T', $factura->fecha)[1]), 'h:i:sa');               
                $factura['fecha']  =  date_format(date_create(explode('T', $factura->fecha)[0]), 'd-m-Y');   
                $factura['totalArticulo']  = $totalArticulos;
            
        
                return response()->json([
                    "mensaje" => "Consulta de factura exitosa.",
                    "data" =>  $factura, 
                    "estatus" =>  Response::HTTP_OK  
                ],  Response::HTTP_OK ); 
            }else{
                return response()->json([
                    "mensaje" =>  "No se encontró factura con el código solicitado.",
                    "data" => null, 
                    "estatus" => Response::HTTP_OK  
                ], Response::HTTP_OK ); 
            }
            
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar factura, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  [], 
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR  
            ], Response::HTTP_INTERNAL_SERVER_ERROR ); 
        }
    }
    

    /**
     * REGISTRAR FACTURA
     *
     * @param  \App\Http\Requests\StoreFacturaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request){

        try {
            /** validamos que el codigo no se repita */
            $codigoExiste = Factura::where('codigo', $request->codigo)->get();
            if( count($codigoExiste) && $request->estatusDeDevolucion == false ){
                return response()->json([
                    "mensaje" => "El codigo de la factura ya EXISTE!",
                    "data" => $codigoExiste[0],
                    "estatus" => Response::HTTP_UNAUTHORIZED
               ], Response::HTTP_UNAUTHORIZED);
            }

            if($request->estatusDeDevolucion){
                Factura::where('codigo', $request->codigo)->delete();
            }

            $resultado = Factura::create($request->all());

            if($resultado){
                // Procedemos a descontar del inventario
                $carritos = Carrito::where("codigo", $request->codigo)->get();
                $totalArticulos = 0;
                foreach ($carritos as $key => $producto) {
                    $cantidadActualProducto = Inventario::where("codigo", $producto->codigo_producto)->get()[0]->cantidad;
                    Inventario::where("codigo", $producto->codigo_producto)->update([
                        "cantidad" =>  $cantidadActualProducto - $producto->cantidad
                    ]);
                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                } 

                $resultado['carrito'] = $carritos;
                $resultado['cliente'] = Cliente::where('identificacion', $request->identificacion)->get()[0];
                $resultado['pos'] = Po::all()[0];
                $resultado['hora']  =  date_format(date_create(explode('T', $resultado->fecha)[1]), 'h:i:sa');               
                $resultado['fecha']  =  date_format(date_create(explode('T', $resultado->fecha)[0]), 'd-m-Y');               
                $resultado['totalArticulo']  = $totalArticulos;


                return response()->json([
                     "mensaje" => "Factura registrada correctamente",
                     "data" => $resultado,
                     "estatus" => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
            }else{
                return response()->json([
                    "mensaje" => "No se registró la Factura, intente de nuevo",
                    "data" => $resultado,
                    "estatus" => Response::HTTP_NOT_FOUND
               ], Response::HTTP_NOT_FOUND);
            }
           

        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al intentar registrar factura, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  $request->request, 
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR 
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
       
    }   


    /** CIERRE DE LA API REST FULL */

 
}

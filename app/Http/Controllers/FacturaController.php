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
use App\Models\Po;
use App\Models\Proveedore;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class FacturaController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }


    /** API */

    public function getCodigoFactura(){
        try {
            $codigo = Helpers::getCodigo('facturas');
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $utilidades = $this->data->utilidades;
            $facturas = Factura::where('codigo', '>', 0)->orderBy('codigo', 'DESC')->get();
           
            if(count($facturas)){
                foreach ($facturas as $key => $factura) {
                    $factura->carrito = Carrito::where('codigo', $factura->codigo)->get();
                    // $factura->total_articulos = Carrito::where('codigo', $factura->codigo)->count();
                }
            }
           
            $pos = count(Po::all()) ? Po::all()[0]: [];
           
            $pathname = Request::path();
            
            return view('admin.facturas.lista', compact( 'facturas','menuSuperior', 'pathname', 'pos', 'utilidades'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    public function listaFacturaPorPagar(){
        $pathname = Request::path();
        $menuSuperior = $this->data->menuSuperior;

        $pagar = FacturaInventario::where([
            "tipo" => 'ENTRADA',
            "concepto" => 'CREDITO',
        ])->get();

        foreach ($pagar as $key => $value) {
           $value['proveedor']= Proveedore::where('codigo', $value->identificacion)->get();
           $value['carrito'] = CarritoInventario::where('codigo', $value->codigo)->get();
            $contador = 0;
           foreach($value['carrito'] as $cantidade){
            $value['totalArticulos'] += $cantidade->cantidad; 
           }
        }
        // return $pagar;
        return view('admin.pagar.lista', compact('pagar', 'menuSuperior', 'pathname'));
    }

    public function listaFacturaPorCobrar(){
        $pathname = Request::path();
        $menuSuperior = $this->data->menuSuperior;

        $cobrar = Factura::where([
            "tipo" => 'SALIDA',
            "concepto" => 'CREDITO',
        ])->get();

        foreach ($cobrar as $key => $value) {
           $value['cliente']= Cliente::where('identificacion', $value->identificacion)->get();
           $value['carrito'] = Carrito::where('codigo', $value->codigo)->get();
            $contador = 0;
           foreach($value['carrito'] as $cantidade){
            $value['totalArticulos'] += $cantidade->cantidad; 
           }
        }
        // return $cobrar;
        return view('admin.cobrar.lista', compact('cobrar', 'menuSuperior', 'pathname'));
    }

    public function imprimirFactura($codigoFactura){
        try {
            $factura = Factura::where('codigo', $codigoFactura)->get();
           
            $carritos = Carrito::where('codigo', $factura->codigo)->get();
            $factura[0]['carrito'] = $carritos;
            $factura[0]['pos'] = Po::all()[0];
            $factura[0]['hora']  =  date_format(date_create(explode(' ', $factura[0]->created_at)[1]), 'h:i:s');               
            $factura[0]['fecha']  =  date_format(date_create(explode(' ', $factura[0]->created_at)[0]), 'd-m-Y');   
         
            $mensaje = count($factura) ? "Consulta de factura exitosa." : "No se hallo un registro de la factura";
            $estatus =  Response::HTTP_OK;
    
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  $factura, 
                "estatus" => $estatus  
            ], $estatus ); 
            
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar factura, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  [], 
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR  
            ], Response::HTTP_INTERNAL_SERVER_ERROR ); 
        }

            // $pathname = Request::path();
            //  return view('admin.facturas.ticket', 
            //      compact(
            //         'factura', 
            //         'pos',
            //         'utilidades'
            //  ));
       
            // $pdf = PDF::loadView(
            //     'admin.facturas.ticket',
            //     compact(
            //         'factura', 
            //         'pos',
            //         'utilidades'
            //     )
            // );
            // return $pdf->download("{$factura->codigo}-{$factura->identificacion}-{$factura->created_at}.pdf");
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFacturaRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(HttpRequest $request){

        
        return response()->json([
            "mensaje" => "data recibida.",
            "data" =>  $request->all(), 
            "estatus" =>Response::HTTP_OK 
        ],Response::HTTP_OK);
    }   

    public function storeDemo(StoreFacturaRequest $request)
    {
        
        try {
            $clientes = Cliente::where('identificacion', $request->identificacion)->get();
            if (count($clientes) == 0) {
                return response()->json([
                    "mensaje" => "El cliente no esta registrado.",
                    "data" =>  [], 
                    "estatus" =>Response::HTTP_NOT_FOUND 
                ],Response::HTTP_NOT_FOUND);
            }else{
                $cliente = $clientes[0]; 
            }

            $carrito = Carrito::where('codigo', $request->codigo)->get();
            if(count($carrito)){
                $resultado = Factura::create($request->all());
                $mensaje = $resultado ? "Se proceso la venta correctamente correctamente." : "No se registro la factura";
                $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
            }else{
                return response()->json([
                    "mensaje" => "La factura no puede ser procesa porque no poseé productos facturados",
                    "data" =>  [], 
                    "estatus" =>Response::HTTP_NOT_FOUND 
                ],Response::HTTP_NOT_FOUND);
            }

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
                $resultado['cliente'] = $cliente;
                $resultado['pos'] = Po::all()[0];
                $resultado['hora']  =  date_format(date_create(explode(' ', $resultado->created_at)[1]), 'h:i:s');               
                $resultado['fecha']  =  date_format(date_create(explode(' ', $resultado->created_at)[0]), 'd-m-Y');               
                
                $resultado['totalArticulo']  = $totalArticulos;
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $resultado, 
                    "estatus" => Response::HTTP_OK  
                ], Response::HTTP_OK ); 
            }else{
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $request->request, 
                    "estatus" => Response::HTTP_OK  
                ], Response::HTTP_OK );
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $utilidades = $this->data->utilidades;
            $facturas= Factura::where('id',$id)->get();
           
            if(count($facturas)){
                foreach ($facturas as $key => $factura) {
                    $factura->carrito = Carrito::where('codigo', $factura->codigo)->get();
                    $contador = 0;
                    foreach ($factura->carrito as $key => $producto) {
                        $contador += $producto->cantidad;
                    }
                    $factura->totalArticulos = $contador;
                    // configuramos los metodos
                    $metodosArr = explode(',', $factura->metodos);
                    $arrayMetodos = [];
                    foreach ($metodosArr as $key => $metodo) {
                         array_push(
                            $arrayMetodos,
                            [
                                "tipo" => explode('|', $metodo)[0],
                                "monto" => explode('|', $metodo)[1]
                            ]
                        );
                    }

                    $factura->metodos = $arrayMetodos;
                }

                $factura = $facturas[0];
              
            }else{
                $mensaje = "El código de la factura no esta registrado, verifique el codigo.";
                $estatus = 404;
                return redirect()->route('admin.facturas.index', compact('mensaje', 'estatus'));
            }
          
            $pos = count(Po::all()) ? Po::all()[0]: [];
            $pathname = Request::path();
         
            return view( 'admin.facturas.ver', compact( 'factura', 'pos', 'utilidades', 'menuSuperior', 'pathname' ) );
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFacturaRequest  $request
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFacturaRequest $request, Factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        //
    }
}

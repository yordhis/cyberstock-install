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
  

    public function getFactura($codigoFactura){
        try {
           
            $factura = Factura::where('codigo', $codigoFactura)->get();
            if(count($factura)){
                $factura = $factura[0];

                $carritos = Carrito::where('codigo', $factura->codigo)->get();
                $cliente = Cliente::where('identificacion', $factura->identificacion)->get();
                if(count($cliente)) $factura['cliente'] = $cliente[0];
                else $factura['cliente'] = ["nombre" => "El Cliente fue eliminado"];
                $factura['carrito'] = $carritos; 
                $factura['pos'] = Po::all()[0];
                $factura['hora']  =  date_format(date_create(explode(' ', $factura->created_at)[1]), 'h:i:s');               
                $factura['fecha']  =  date_format(date_create(explode(' ', $factura->created_at)[0]), 'd-m-Y');   
             
            
        
                return response()->json([
                    "mensaje" => "Consulta de factura exitosa.",
                    "data" =>  $factura, 
                    "estatus" =>  Response::HTTP_OK  
                ],  Response::HTTP_OK ); 
            }else{
                return response()->json([
                    "mensaje" =>  "No se hallo un registro de la factura",
                    "data" =>  $codigoFactura, 
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFacturaRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(HttpRequest $request){

        try {
            /** validamos que el codigo no se repita */
            $codigoExiste = Factura::where('codigo', $request->codigo)->get();
            if( count($codigoExiste) ){
                return response()->json([
                    "mensaje" => "El codigo de la factura ya EXISTE!",
                    "data" => $codigoExiste[0],
                    "estatus" => Response::HTTP_UNAUTHORIZED
               ], Response::HTTP_UNAUTHORIZED);
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
                $resultado['hora']  =  date_format(date_create(explode(' ', $resultado->created_at)[1]), 'h:i:s');               
                $resultado['fecha']  =  date_format(date_create(explode(' ', $resultado->created_at)[0]), 'd-m-Y');               
                
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

                }

                $factura = $facturas[0];
              
            }else{
                $mensaje = "El código de la factura no esta registrado, verifique el codigo.";
                $estatus = 404;
                return redirect()->route('admin.facturas.index', compact('mensaje', 'estatus'));
            }
          
            $pos = count(Po::all()) ? Po::all()[0]: [];
            $pathname = Request::path();
            $pathname = explode('/', $pathname)[0] . '/ver';
           
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

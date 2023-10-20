<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Models\Carrito;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Inventario;
use App\Models\Po;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class FacturaController extends Controller
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
        try {
        
            $menuSuperior = $this->data->menuSuperior;
            $utilidades = $this->data->utilidades;
            $facturas = Factura::where('codigo', '>', 0)->orderBy('codigo', 'DESC')->get();
           
            if(count($facturas)){
                foreach ($facturas as $key => $factura) {
                    $factura->carrito = Carrito::where('codigo', $factura->codigo)->get();
                    $factura->total_articulos = Carrito::where('codigo', $factura->codigo)->count();
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

    public function imprimirFactura($codigoFactura){
        $factura = Factura::where('codigo', $codigoFactura)->get()[0];
       
        $factura->carrito = Carrito::where('codigo', $factura->codigo)->get();
        $factura->total_articulos = Carrito::where('codigo', $factura->codigo)->count();
        $pos = Po::all();
        $utilidades = $this->data->utilidades;

            // $pathname = Request::path();
            //  return view('admin.facturas.ticket', 
            //      compact(
            //         'factura', 
            //         'pos',
            //         'utilidades'
            //  ));
       
            $pdf = PDF::loadView(
                'admin.facturas.ticket',
                compact(
                    'factura', 
                    'pos',
                    'utilidades'
                )
            );
            return $pdf->stream("{$factura->codigo}-{$factura->identificacion}-{$factura->created_at}.pdf");
    
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
    public function store(StoreFacturaRequest $request)
    {
        
        try {
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
                // obtenemos los datos del POS
                $pos = Po::all()[0];

                // Procedemos a descontar del inventario
                $carritos = Carrito::where("codigo", $request->codigo)->get();
                foreach ($carritos as $key => $producto) {
                    $cantidadActualProducto = Inventario::where("codigo", $producto->codigo_producto)->get()[0]->cantidad;
                    Inventario::where("codigo", $producto->codigo_producto)->update([
                        "cantidad" =>  $cantidadActualProducto - $producto->cantidad
                    ]);
                } 
                $resultado['carrito'] = $carrito;
                $resultado['pos'] = $pos;
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
    public function show(Factura $factura)
    {
        //
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

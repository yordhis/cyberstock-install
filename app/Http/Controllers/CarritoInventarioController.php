<?php

namespace App\Http\Controllers;

use App\Models\CarritoInventario;
use App\Http\Requests\StoreCarritoInventarioRequest;
use App\Http\Requests\UpdateCarritoInventarioRequest;
use App\Models\Carrito;
use App\Models\DataDev;
use App\Models\FacturaInventario;
use App\Models\Helpers;
use App\Models\Inventario;
use App\Models\Proveedore;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;

class CarritoInventarioController extends Controller
{

    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreCarritoInventarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    // ENTRADAS
    public function store(StoreCarritoInventarioRequest $request)
    {
        try {
          
            $menuSuperior = $this->data->menuSuperior;

            // Validadmos que el proveedor exista
            $proveedorExiste = Proveedore::where('codigo', $request->identificacion)->get();
            if (count($proveedorExiste) == 0) {
                 return redirect()->route('admin.inventarios.crearEntrada', [
                    "identificacion" => $request->identificacion,
                    "codigo_producto" => $request->codigo_producto,
                    "conceptoDeMovimiento" => $request->conceptoDeMovimiento,
                    "mensaje"=>"El Rif o Documento de identidad del proveedor no esta registrado.",
                    "mensajeInventario"=> "Proveedor no esta registrado, registreló para procesar la entrada.",
                    "estatus" => 404
                ]);
            }

            // Validamos que la factura no este repetida
            $facturaRepetidadDelProveedor = FacturaInventario::where([
                "codigo" => $request->codigo, 
                "identificacion" => $request->identificacion, 
            ])->get();
        
            if(count($facturaRepetidadDelProveedor)){
                return redirect()->route('admin.inventarios.crearEntrada', [
                    "identificacion" => $request->identificacion,
                    "codigo_producto" => $request->codigo_producto,
                    "conceptoDeMovimiento" => $request->conceptoDeMovimiento,
                    "mensaje"=>"Esta factura ya esta registrada, verifique que halla ingresado el numero de factura correctamente.",
                    "mensajeInventario"=> "Número de factura del proveedor ya esta registrado.",
                    "estatus" => 404
                ]);
            }

            // Añadimos al carrito el producto
            CarritoInventario::create($request->all());

            // $carritos = CarritoInventario::where('codigo', $request->codigo)->get();
            // $codigo = $request->codigo;
            return redirect()->route('admin.inventarios.crearEntrada', [ 
                "conceptoDeMovimiento" => $request->conceptoDeMovimiento
            ]);
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear la marca, ");
            return view('errors.404', compact('mensajeError'));
        }

    }

    public function storeSalida(HttpRequest $request)
    {
        try {
          
            $menuSuperior = $this->data->menuSuperior;

            // Validadmos que la cantida no sobre pase la del inventario
            if($request->tipoTransaccion == "SALIDA"){
                $productoEnInventario = Inventario::where('codigo', $request->codigo_producto)->get();
                if(count($productoEnInventario)){
                    if($request->cantidad > $productoEnInventario[0]->cantidad){
                        return redirect()->route('admin.inventarios.crearSalida', [
                        "identificacion" => $request->identificacion,
                        "codigo_producto" => $request->codigo_producto,
                        "mensaje"=>"La Existencia es insuficiente para agregar el producto {$request->descripcion}.",
                        "mensajeInventario"=>"La Existencia del producto {$productoEnInventario[0]->descripcion} es de ({$productoEnInventario[0]->cantidad} Unidades).",
                        "estatus" => 404
                        ]);
                    }   
                }
            }

            if ($request->conceptoDeMovimiento == 'CONSUMO') {
                CarritoInventario::create($request->all());
                
            }else{
                // Creamos el carrito de ventas
                Carrito::create([
                    "codigo" => $request->codigo_factura, 
                    "codigo_producto" => $request->codigo_producto,
                    "identificacion"  => $request->identificacion,
                    "cantidad"  => $request->cantidad,
                    "costo"  => $request->costo,
                    "subtotal"  => $request->subtotal,
                    "descripcion"  => $request->descripcion
                ]);

                CarritoInventario::create($request->all());

            }

            $conceptoDeMovimiento = $request->conceptoDeMovimiento;

            return redirect()->route('admin.inventarios.crearSalida', compact('conceptoDeMovimiento'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear la carrito de salida, ");
            return view('errors.404', compact('mensajeError'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function show(CarritoInventario $carritoInventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function edit(CarritoInventario $carritoInventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarritoInventarioRequest  $request
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarritoInventarioRequest $request, CarritoInventario $carritoInventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarritoInventario $carritoInventario)
    {
        try {
            $identificacion = $carritoInventario->identificacion;
            $menuSuperior = $this->data->menuSuperior;
            $carritoInventario->delete();

            
           
            return redirect()->route('admin.inventarios.crearEntrada', compact('identificacion'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al eliminar la producto del carrito, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Http\Requests\StoreCarritoRequest;
use App\Http\Requests\UpdateCarritoRequest;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Inventario;
use Illuminate\Support\Facades\Request;

class CarritoController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }

    public function eliminarCarritoCompleto($codigo){
        try {
            $carritoExiste = Carrito::where('codigo', $codigo)->get();
            if(count($carritoExiste)){
                $estatusEliminar = Carrito::where('codigo', $codigo)->delete();
                $mensaje = $estatusEliminar ? "La Factura se eliminó correctamente." : "No se pudo eliminar la factura";
                $estatus = $estatusEliminar ? 201 : 404;
            }else{
                $mensaje = "La factura no se ha registrado, no se encontro factura que eliminar.";
                $estatus = 404;
            }
      
            return redirect()->route('admin.pos.index', compact('mensaje', 'estatus') );
    
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Elimianr factura, ");
            return view('errors.404', compact('mensajeError'));
        }
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
     * @param  \App\Http\Requests\StoreCarritoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarritoRequest $request)
    {
        try {
           
            $menuSuperior = $this->data->menuSuperior;

            // Validadmos que la cantida no sobre pase la del inventario
            $productoEnInventario = Inventario::where('codigo', $request->codigo_producto)->get();
            if(count($productoEnInventario)){
                if($request->cantidad > $productoEnInventario[0]->cantidad){
                   
                    return redirect()->route('admin.pos.index', [
                    "identificacion" => $request->identificacion,
                    "codigo_producto" => $request->codigo_producto,
                    "mensaje"=>"La Existencia es insuficiente para agregar el producto {$request->descripcion}.",
                    "mensajeInventario"=>"La Existencia del producto {$productoEnInventario[0]->descripcion} es de ({$productoEnInventario[0]->cantidad} Unidades).",
                    "estatus" => 404
                    ]);
                }   
            }

            // Validamos que el Cliente esten registrados
            $razonSocial = Cliente::where('identificacion', $request->identificacion)->get();
            if(count($razonSocial) == 0){

                    return redirect()->route('admin.pos.index', [
                    "identificacion" => $request->identificacion,
                    "codigo_producto" => $request->codigo_producto,
                    "mensaje"=>"El cliente de la identificacion {$request->identificacion} no esta registrado, por favor registrelo.",
                    "mensajeInventario"=>"No se pudo agregar producto a la factura el cliente no esta registrado.",
                    "estatus" => 404
                    ]);
            }

            Carrito::create($request->all());

            $carritos = Carrito::where('codigo', $request->codigo)->get();
            $codigo = $request->codigo;
            return redirect()->route('admin.pos.index');
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al agregar producto al carrito factura, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function show(Carrito $carrito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function edit(Carrito $carrito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarritoRequest  $request
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarritoRequest $request, Carrito $carrito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carrito $carrito)
    {
        try {
            $identificacion = $carrito->identificacion;
            $menuSuperior = $this->data->menuSuperior;
            $carrito->delete();

            $carritos = Carrito::where('codigo', $carrito->codigo)->get();
            $codigo = $carrito->codigo;
            return redirect()->route('admin.pos.index', compact('identificacion'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al eliminar la producto del carrito, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
}

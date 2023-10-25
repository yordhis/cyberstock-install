<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Http\Requests\StoreInventarioRequest;
use App\Http\Requests\UpdateInventarioRequest;
use App\Models\Carrito;
use App\Models\DataDev;
use App\Models\Factura;
use App\Models\Helpers;
use App\Models\Po;
use App\Models\Proveedore;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class InventarioController extends Controller
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
            $inventarios = Helpers::setNameElementId(Inventario::where("estatus", ">=", 1)->orderBy('id', 'desc')->get(), 'id,nombre', 'categorias,marcas');
            // return $inventarios;
            $utilidades = $this->data->utilidades;
            $menuSuperior = $this->data->menuSuperior;
            $pathname = FacadesRequest::path();

            return view("admin.inventarios.lista", compact('menuSuperior', 'inventarios', 'utilidades', 'pathname'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
      
    }
    /**
     * Se consideran entradas los siguientes ID Y CONCEPTOS
     * "COMPRA", // 5
     * "DEVOLUCION", // 6
     * "INICIALIZACION" // 7
     * 
     * todos estos datos de entrada se toman de la tabla de facturas
     */
    public function listaEntradas(){
        $utilidades = $this->data->utilidades;
        $menuSuperior = $this->data->menuSuperior;
        $pathname = FacadesRequest::path();
        $pos = Po::all()[0];
        
            $entradas = Factura::where([
                "tipo" => 5
            ])->get();
            foreach ($entradas as $key => $salida) {
                $salida->carrito = Carrito::where("codigo", $salida->codigo)->get();
            }
            // return $entradas;
            return view('admin.entradas.lista', compact('menuSuperior', 'utilidades', 'entradas', 'pos', 'pathname'));
    }

    /**
     * Se consideran entradas los siguientes ID Y CONCEPTOS
     *  "VENTA", // 1
     *  "CONSUMO", // 2
     *  "DAÑADO", // 3
     *  "CREDITO", // 4
     * todos estos datos de entrada se toman de la tabla de facturas
     */
    public function listaSalidas(){
        $utilidades = $this->data->utilidades;
        $menuSuperior = $this->data->menuSuperior;
        $pathname = FacadesRequest::path();
        $pos = Po::all()[0];
        
            $salidas = Factura::where([
                "tipo" => 1
            ])->get();
            foreach ($salidas as $key => $salida) {
                $salida->carrito = Carrito::where("codigo", $salida->codigo)->get();
            }
            // return $salidas;
            return view('admin.salidas.lista', compact('menuSuperior', 'utilidades', 'salidas', 'pos', 'pathname'));
        
    }

    public function crearEntrada(Request $request)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $tiposEntradas = $this->data->tiposEntradas;
            $tasa = number_format(floatval($this->data->utilidades[0]->tasa), 2);
            $iva = $this->data->utilidades[0]->iva;
            $codigo = Helpers::getCodigo('factura_inventarios');
            $pathname = $request->path();

            $carritos = Carrito::where([
                'codigo' => $request->codigo, 
                'identificacion' => $request->identificacion, 
            ])->get();

            if (count($carritos)) {
                foreach ($carritos as $key => $producto) {
                    $producto['proveedor'] = Proveedore::where('codigo', $producto->identificacion)->get()[0];
                    $producto->costoBs = number_format(floatval($producto->costo) * $tasa, 2, ',', '.') . " Bs";
                    $producto->subtotalBs = number_format(floatval($producto->subtotal) * $tasa, 2, ',', '.') . " Bs";
                }
            }

            return view('admin.entradas.index', compact('request', 'codigo', 'carritos', 'tiposEntradas', 'menuSuperior', 'pathname', 'tasa', 'iva'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar mostrar formulario de entradas, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    public function crearSalida(Request $request)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $tiposEntradas = $this->data->tiposEntradas;
            $pathname = $request->path();
            return view('admin.salidas.index', compact('request', 'tiposEntradas', 'menuSuperior', 'pathname'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar mostrar formulario de salidas, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
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
     * @param  \App\Http\Requests\StoreInventarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInventarioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInventarioRequest  $request
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInventarioRequest $request, Inventario $inventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        try {
            $inventario->delete();
            $mensaje = "Se Eliminó correctamente";
            $estatus = 200;
            return redirect()->route('admin.inventarios.index', compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar eliminar producto del inventario, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }
}

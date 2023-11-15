<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Http\Requests\StoreInventarioRequest;
use App\Http\Requests\UpdateInventarioRequest;
use App\Models\{
    Carrito,
    CarritoInventario,
    Cliente,
    DataDev,
    Factura,
    FacturaInventario,
    Helpers,
    Po,
    Proveedore,
    Utility
};

use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            $menuSuperior = $this->data->menuSuperior;
            $pathname = FacadesRequest::path();
            return view("admin.inventarios.lista", compact('menuSuperior', 'pathname'));

        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar inventario, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
      
    }

    /** Api */
    public function getInventariosFiltro(Request $request){
        try {
            $tasa = $this->data->utilidades[0]->tasa;

            // filtramos por la descripcion
            if(request('filtro')){
                    $resultados = Inventario::where("{$request->campo}", 'like', "%{$request->filtro}%")->orderBy('id', 'desc')->paginate(100);
                    if (count($resultados)) {
                       $inventarios = Helpers::setNameElementId( $resultados , 'id,nombre', 'categorias,marcas' );
                       return response()->json([
                           "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE",
                           "data" => $inventarios,
                           "tasa" => $tasa,
                           "estatus" => Response::HTTP_OK
                       ], Response::HTTP_OK);
                    }else{
                        return response()->json([
                            "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE, NO HAY EXISTE ESTE PRODUCTO EN EL INVENTARIO.",
                            "data" => $resultados,
                            "tasa" => $tasa,
                            "estatus" => Response::HTTP_OK
                        ], Response::HTTP_OK);
                    }
            }

      

        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error en la API al retornar los datos del Proveedor en el método getProveedor,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
    
    public function getInventarios(Request $request){
        try {
            $tasa = $this->data->utilidades[0]->tasa;

            // filtramos por la descripcion
            if(request('filtro')){
                    $resultados = Inventario::where('descripcion', 'like', "%{$request->filtro}%")->orderBy('id', 'desc')->paginate(15);
                    if (count($resultados)) {
                       $inventarios = Helpers::setNameElementId( $resultados , 'id,nombre', 'categorias,marcas' );
                       return response()->json([
                           "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE",
                           "data" => $inventarios,
                           "tasa" => $tasa,
                           "estatus" => Response::HTTP_OK
                       ], Response::HTTP_OK);
                    }else{
                        return response()->json([
                            "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE, NO HAY EXISTE ESTE PRODUCTO EN EL INVENTARIO.",
                            "data" => $resultados,
                            "tasa" => $tasa,
                            "estatus" => Response::HTTP_OK
                        ], Response::HTTP_OK);
                    }
            }

            // PAGINAMOS LOS PRODUCTOS DEL INVENTARIO
            $inventarios = Helpers::setNameElementId(Inventario::where("estatus", ">=", 1)->orderBy('id', 'desc')->paginate(15), 'id,nombre', 'categorias,marcas');
            return response()->json([
                "mensaje" => "CONSULTA AL INVENTARIO EXITOSA",
                "data" => $inventarios,
                "tasa" => $tasa,
                "estatus" => Response::HTTP_OK
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error en la API al retornar los datos del Proveedor en el método getProveedor,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => $inventarios,
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function editarProductoDelInventario( Request $request ){
        try {

            $estatus = Inventario::where( 'id', $request->id )->update($request->all());
            if($estatus){
                return response()->json([
                    "mensaje" => "LOS DATOS DE INVENTARIO DEL PRODUCTO SE ACTUALIZÓ EXITOSAMENTE",
                    "data" => $estatus,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    "mensaje" => "LOS DATOS NO SE GUARDARON!",
                    "data" => $estatus,
                    "estatus" => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }

        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar ACTUALIZAR un producto del inventario, ");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteProductoDelInventario( $id ){
        try {

            $estatus = Inventario::where( 'id', $id )->delete();
            if($estatus){
                return response()->json([
                    "mensaje" => "PRODUCTO ELIMINADO DEL INVENTARIO EXITOSAMENTE",
                    "data" => [],
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    "mensaje" => "EL PRODUCTO NO SE PUDO ELIMINAR DEL INVENTARIO!",
                    "data" => [],
                    "estatus" => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }

        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar elimianr un producto del inventario, ");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        
            $entradas = FacturaInventario::where([
                "tipo" => "ENTRADA"
            ])->get();

            foreach ($entradas as $key => $entrada) {
                $entrada->carrito = CarritoInventario::where("codigo", $entrada->codigo)->get();
               
                $totalArticulos = 0;
                foreach ($entrada->carrito as $key => $articulos) {
                    $totalArticulos = $totalArticulos + $articulos->cantidad;
                }
                $entrada->totalArticulos = $totalArticulos;
                $entrada->proveedor = Proveedore::where("codigo", $entrada->identificacion)->get();
            }
            
            return view('admin.entradas.lista', compact('menuSuperior', 'utilidades', 'entradas', 'pathname'));
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
        
        $salidas = FacturaInventario::where([
            "tipo" => "SALIDA"
        ])->get();

        foreach ($salidas as $key => $salida) {
            $salida->carrito = CarritoInventario::where("codigo", $salida->codigo)->get();
           
            $totalArticulos = 0;
            foreach ($salida->carrito as $key => $articulos) {
                $totalArticulos = $totalArticulos + $articulos->cantidad;
            }
            $salida->totalArticulos = $totalArticulos;
            $salida->cliente = Cliente::where("identificacion", $salida->identificacion)->get();
        }
          
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

            $carritos = CarritoInventario::where([
                'codigo' => $codigo
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
            $tasa = number_format(floatval($this->data->utilidades[0]->tasa), 2);
            $iva = $this->data->utilidades[0]->iva;
            $codigo = Helpers::getCodigo('factura_inventarios');
            $codigoFactura = Helpers::getCodigo('facturas');
            $pathname = $request->path();

            $carritos = CarritoInventario::where([
                'codigo' => $codigo
            ])->get();

            if (count($carritos)) {
                foreach ($carritos as $key => $producto) {
                    $producto['cliente'] = Cliente::where('identificacion', $producto->identificacion)->get();
                    $producto->costoBs = number_format(floatval($producto->costo) * $tasa, 2, ',', '.') . " Bs";
                    $producto->subtotalBs = number_format(floatval($producto->subtotal) * $tasa, 2, ',', '.') . " Bs";
                }
            }

            return view('admin.salidas.index', compact('request', 'carritos', 'tasa', 'iva', 'codigo', 'codigoFactura', 'tiposEntradas', 'menuSuperior', 'pathname'));
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

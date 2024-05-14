<?php

namespace App\Http\Controllers;

use App\Exports\InventarioExportar;
use App\Models\Inventario;
use App\Http\Requests\StoreInventarioRequest;
use App\Http\Requests\UpdateInventarioRequest;
use App\Imports\InventarioImportar;
use App\Models\{
    Carrito,
    CarritoInventario,
    Categoria,
    Cliente,
    DataDev,
    Factura,
    FacturaInventario,
    Helpers,
    Marca,
    Pago,
    Po,
    Proveedore,
    Utilidade,
    Utility
};

use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use \Excel;

class InventarioController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }

    /** vista importar datos */
    public function importarCreate(){
        $menuSuperior = $this->data->menuSuperior;
        $respuesta = $this->data->respuesta;
        return view("admin.inventarios.importar", compact('menuSuperior', 'respuesta'));
    }

    /** importar la data del archivo */
    public function importarExcel(Request $request){
        try {
            Excel::import(new InventarioImportar, $request->file('file'));
            $mensaje = "Importación de datos realizada correctamente.";
            $estatus = Response::HTTP_OK;
            return redirect()->route('admin.importar.create');

        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", error al importar inventario.");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return redirect()->route('admin.importar.create')->with(compact('mensaje', 'estatus'));
        }
       
    }   

    /** Exportar inventario */
    public function exportarInventario() 
    {
        return Excel::download(new InventarioExportar, 'inventario.xlsx');
    }
    /**
     *  Display a listing of the resource.
     *  DESPLEGAMOS LA LISTA DE RECURSOS
     * @return \Illuminate\Http\Response
     * 
     * @return pathname
     * @return menuSuperior
     */
    public function index()
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $pathname = FacadesRequest::path();
            $categorias = Categoria::all();
            $marcas = Marca::all();
            return view("admin.inventarios.lista", compact('menuSuperior', 'pathname', 'categorias', 'marcas'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar inventario, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    public function getInventarioVendedor(){
        $menuSuperior = $this->data->menuSuperior;
        $categorias = Categoria::all();
        $marcas = Marca::all();
        $pathname = FacadesRequest::path();
        return view("admin.inventarios.listaVenedor", compact('menuSuperior', 'pathname', 'categorias', 'marcas'));
    }

    /** API REST FULL */
    public function getInventariosFiltroAll(Request $request)
    {
        try {
            $tasa = Utilidade::all()[0]->tasa;
            // filtramos por la descripcion
            if (request('filtro')) {
                /** Buscamos por codigo de barra */
                foreach ($request->campo as $key => $campo) {
                    switch ($campo) {
                        case 'codigo':
                            $resultados = Inventario::where("{$campo}", $request->filtro)->get();
                            $resultados =   Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                            if (count($resultados)) {
                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR CODIGO",
                                    "data" =>   $resultados,
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }
                            break;
                        case 'descripcion':

                            if($request->id_categoria > 0 && $request->id_marca > 0){
                         
                                $resultados = Inventario::where([
                                    "id_categoria" => $request->id_categoria,
                                    "id_marca" => $request->id_marca
                                ])
                                ->where("{$campo}", "like", "%{$request->filtro}%")
                                ->orderBy('descripcion', 'asc')->get();

                
                            }elseif ($request->id_categoria > 0){
                                $resultados = Inventario::where([
                                    "id_categoria" => $request->id_categoria,
                                ])
                                ->where("{$campo}", "like", "%{$request->filtro}%")
                                ->orderBy('descripcion', 'asc')->get();

                            }elseif ($request->id_marca > 0) {
                                
                                $resultados = Inventario::where([
                                    "id_marca" => $request->id_marca
                                ])
                                ->where("{$campo}", "like", "%{$request->filtro}%")
                                ->orderBy('descripcion', 'asc')->get();

                            }else{
                                $resultados = Inventario::where("{$campo}", 'like', "%{$request->filtro}%")->orderBy('descripcion', 'asc')->get();
                            }

                            
            
                            $resultados = Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                          

                            if (count($resultados)) {

                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                                    "data" => $resultados,
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }else{
                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                                    "data" => $resultados,
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }
                            break;

                        default:
                            return response()->json([
                                "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE, NO EXISTE ESTE PRODUCTO EN EL INVENTARIO.",
                                "data" => [
                                    "data" => [],
                                    "total" => 0
                                ],
                                "tasa" => $tasa,
                                "estatus" => Response::HTTP_OK
                            ], Response::HTTP_OK);
                            break;
                    }
                }
            }else{
                if ($request->id_categoria > 0) {
                    $resultados = Inventario::where([
                        "id_categoria" => $request->id_categoria,
                    ])
                    ->orderBy('descripcion', 'asc')->get();
                }elseif ($request->id_marca > 0) {
                    $resultados = Inventario::where([
                        "id_marca" => $request->id_marca,
                    ])
                    ->orderBy('descripcion', 'asc')->get();
                }else{
                    return response()->json([
                        "mensaje" => "El filtro no poseé parametros de busquedas",
                        "data" => ["data" => []],
                        "estatus" => Response::HTTP_NOT_FOUND
                    ], Response::HTTP_NOT_FOUND);
                }

                $resultados = Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');

                return response()->json([
                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                    "data" => $resultados,
                    "tasa" => $tasa,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "ERROR AL FILTRAR LA BUSQUEDA DEL PRODUCTO,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => ["data" => []],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getInventariosFiltro(Request $request)
    {
        try {
            $numeroDePagina = 15;
            if(isset($request->numeroDePagina)){
                $numeroDePagina = $request->numeroDePagina;
            }

            $tasa = Utilidade::all()[0]->tasa;
            // filtramos por la descripcion
            if (request('filtro')) {
                /** Buscamos por codigo de barra */
                foreach ($request->campo as $key => $campo) {
                    switch ($campo) {
                        case 'codigo':
                            $resultados = Inventario::where("{$campo}", $request->filtro)->paginate($numeroDePagina);
                            $resultados =   Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                            if (count($resultados)) {
                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR CODIGO",
                                    "data" =>   $resultados,
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }
                            break;
                        case 'descripcion':

                            if($request->id_categoria > 0 && $request->id_marca > 0){
                         
                                $resultados = Inventario::where([
                                    "id_categoria" => $request->id_categoria,
                                    "id_marca" => $request->id_marca
                                ])
                                ->where("{$campo}", "like", "%{$request->filtro}%")
                                ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);

                
                            }elseif ($request->id_categoria > 0){
                                $resultados = Inventario::where([
                                    "id_categoria" => $request->id_categoria,
                                ])
                                ->where("{$campo}", "like", "%{$request->filtro}%")
                                ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);

                            }elseif ($request->id_marca > 0) {
                                
                                $resultados = Inventario::where([
                                    "id_marca" => $request->id_marca
                                ])
                                ->where("{$campo}", "like", "%{$request->filtro}%")
                                ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);

                            }else{
                                $resultados = Inventario::where("{$campo}", 'like', "%{$request->filtro}%")->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                            }

                            
            
                            $resultados = Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                          

                            if (count($resultados)) {

                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                                    "data" => $resultados,
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }else{
                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                                    "data" => $resultados,
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }
                            break;

                        default:
                            return response()->json([
                                "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE, NO EXISTE ESTE PRODUCTO EN EL INVENTARIO.",
                                "data" => [
                                    "data" => [],
                                    "total" => 0
                                ],
                                "tasa" => $tasa,
                                "estatus" => Response::HTTP_OK
                            ], Response::HTTP_OK);
                            break;
                    }
                }
            }else{
                if ($request->id_categoria > 0) {
                    $resultados = Inventario::where([
                        "id_categoria" => $request->id_categoria,
                    ])
                    ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                }elseif ($request->id_marca > 0) {
                    $resultados = Inventario::where([
                        "id_marca" => $request->id_marca,
                    ])
                    ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                }else{
                    return response()->json([
                        "mensaje" => "El filtro no poseé parametros de busquedas",
                        "data" => ["data" => []],
                        "estatus" => Response::HTTP_NOT_FOUND
                    ], Response::HTTP_NOT_FOUND);
                }

                $resultados = Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');

                return response()->json([
                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                    "data" => $resultados,
                    "tasa" => $tasa,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "ERROR AL FILTRAR LA BUSQUEDA DEL PRODUCTO,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => ["data" => []],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getInventarios(Request $request)
    {
        try {
            $tasa = Utilidade::all()[0]->tasa;
            // PAGINAMOS LOS PRODUCTOS DEL INVENTARIO
            $inventarios = Helpers::setNameElementId(Inventario::where("estatus", ">=", 1)->orderBy('descripcion', 'asc')->paginate(15), 'id,nombre', 'categorias,marcas');
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

    public function editarProductoDelInventario(Request $request)
    {
        try {

            $estatus = Inventario::where('id', $request->id)->update($request->all());
            if ($estatus) {
                return response()->json([
                    "mensaje" => "LOS DATOS DE INVENTARIO DEL PRODUCTO SE ACTUALIZÓ EXITOSAMENTE",
                    "data" => $estatus,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            } else {
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

    public function deleteProductoDelInventario($id)
    {
        try {
            $estatus = Inventario::where('id', $id)->delete();
            if ($estatus) {
                return response()->json([
                    "mensaje" => "PRODUCTO ELIMINADO DEL INVENTARIO EXITOSAMENTE",
                    "data" => [],
                    "url" => back(),
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "mensaje" => "EL PRODUCTO NO SE PUDO ELIMINAR DEL INVENTARIO!",
                    "data" => back(),
                    "estatus" => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar eliminar un producto del inventario, ");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /** CIERRE DE API REST FULL */

    /**
     * Se consideran entradas los siguientes ID Y CONCEPTOS
     * "COMPRA", // 5
     * "DEVOLUCION", // 6
     * "INICIALIZACION" // 7
     * 
     * todos estos datos de entrada se toman de la tabla de facturas
     */
    public function listaEntradas()
    {
    
        $menuSuperior = $this->data->menuSuperior;

        $entradas = FacturaInventario::where([
            "tipo" => "ENTRADA"
        ])->orderBy('codigo', 'desc')->get();

        foreach ($entradas as $key => $entrada) {
            $entrada->carrito = CarritoInventario::where("codigo", $entrada->codigo)->get();
            $totalArticulos = 0;
            foreach ($entrada->carrito as $key => $articulos) {
                $totalArticulos = $totalArticulos + $articulos->cantidad;
            }
            $entrada->totalArticulos = $totalArticulos;
            $entrada->proveedor = count(Proveedore::where("codigo", $entrada->identificacion)->get()) 
                                ? Proveedore::where("codigo", $entrada->identificacion)->get() 
                                : [] ;
        }

        return view( 'admin.entradas.lista', compact('menuSuperior', 'entradas') );
    }

    /**
     * Se consideran entradas los siguientes ID Y CONCEPTOS
     *  "VENTA", // 1
     *  "CONSUMO", // 2
     *  "DAÑADO", // 3
     *  "CREDITO", // 4
     * todos estos datos de entrada se toman de la tabla de facturas
     */
    public function listaSalidas()
    {
    
        $menuSuperior = $this->data->menuSuperior;
        $pos = Po::all()[0];

        $salidas = FacturaInventario::select('factura_inventarios.codigo', 'factura_inventarios.tipo',  'factura_inventarios.*',
        'clientes.identificacion', 'clientes.tipo as tipo_documento', 'clientes.nombre', 'clientes.telefono', 'clientes.direccion', 'clientes.correo'
        )
        ->join('clientes', 'clientes.identificacion', '=', 'factura_inventarios.identificacion')
        ->where("factura_inventarios.tipo", '=', "SALIDA")->orderBy('factura_inventarios.codigo', 'desc')->get();
       
        foreach ($salidas as $key => $salida) {
            $salida->carrito = CarritoInventario::where("codigo", $salida->codigo)->get();
           
            $salida->fecha = date_format(date_create($salida->fecha), 'd-m-Y h:i:sa');
            $totalArticulos = 0;
            foreach ($salida->carrito as $key => $articulos) {
                $totalArticulos = $totalArticulos + $articulos->cantidad;
            }
            $salida->totalArticulos = $totalArticulos;
        }
        
        
        return view('admin.salidas.lista', compact('menuSuperior', 'salidas', 'pos'));
        
    }

    /** renderiza la vista del pos de compra */
    public function crearEntrada(Request $request)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $pathname = $request->path();
            return view('admin.entradas.index', compact('menuSuperior', 'pathname'));
        } catch (\Throwable $th) {
          
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar mostrar formulario de entradas, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /** RENDERIZANDO VISTA DE POS DE SALIDA */
    public function crearSalida(Request $request)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $pathname = $request->path();
            return view('admin.salidas.index', compact( 'menuSuperior', 'pathname'));

        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar mostrar formulario de salidas, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /** Eliminar factura de inventario */
    public function eliminarFacturaInventario($codigo){
        try {
            $codigo_factura = FacturaInventario::where('codigo', $codigo)->get()[0]->codigo_factura;
            Pago::where('codigo_factura', $codigo_factura)->delete();
            CarritoInventario::where('codigo', $codigo)->delete();
            FacturaInventario::where('codigo', $codigo)->delete();

            $parametros = [
                    "mensaje" => "La factura de inventario se elimino",
                    "estatus" => Response::HTTP_OK
            ];
            $pathnamePrevio = explode("/",url()->previous())[count(explode("/",url()->previous()))-1];
            if(count(explode("?", $pathnamePrevio)) == 2) $pathnamePrevio = explode("?", $pathnamePrevio)[0];

            /** registramos movimiento al usuario - probado*/
            Helpers::registrarMovimientoDeUsuario(request(), Response::HTTP_OK,"Acción de Eliminar facturar de inventario (entra/salida), código de movimiento: ({$codigo})");

            return  redirect()->route("admin.inventarios.{$pathnamePrevio}", $parametros);
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar eliminar factura de inventario. ");
            $pathnamePrevio = explode("/",url()->previous())[count(explode("/",url()->previous()))-1];
            return  redirect()->route("admin.inventarios.{$pathnamePrevio}", [
                "mensaje" => $errorInfo,
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
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

             /** registramos movimiento al usuario */
             Helpers::registrarMovimientoDeUsuario(request(), Response::HTTP_OK,
             "Acción de Eliminar producto del inventario, codigo del producto ({$inventario->codigo})");

            return redirect()->route('admin.inventarios.index', compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar eliminar producto del inventario, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }
}

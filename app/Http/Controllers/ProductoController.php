<?php

namespace App\Http\Controllers;

use App\Models\{
    Categoria,
    DataDev,
    Producto,
    Helpers,
    Inventario,
    Marca,
    Utilidade
};
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ProductoController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }

    /**
     * DESPLEGA LA VISTA DE LA LISTA DE PRODUCTOS
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HttpRequest $request)
    {

        try {
            if ($request->filtro) {

                $productos = Producto::join('categorias', 'categorias.id', '=', 'productos.id_categoria')
                    ->join('marcas', 'marcas.id', '=', 'productos.id_marca')
                    ->select(
                        'productos.id',
                        'productos.codigo',
                        'productos.descripcion',
                        'productos.imagen',
                        'productos.fecha_vencimiento',
                        'productos.id_categoria',
                        'productos.id_marca',
                        'productos.created_at as creado',
                        'productos.updated_at as actualizado',
                        'categorias.id as id_cat',
                        'categorias.nombre as categoria',
                        'marcas.id as id_mar',
                        'marcas.nombre as marca',
                    )
                    ->where('productos.codigo', $request->filtro)
                    ->orWhere('productos.descripcion', 'LIKE', "%{$request->filtro}%")
                    ->orderBy('productos.id', 'desc')
                    ->paginate(12);
            } else {

                $productos = Producto::join('categorias', 'categorias.id', '=', 'productos.id_categoria')
                    ->join('marcas', 'marcas.id', '=', 'productos.id_marca')
                    ->select(
                        'productos.id',
                        'productos.codigo',
                        'productos.descripcion',
                        'productos.imagen',
                        'productos.fecha_vencimiento',
                        'productos.id_categoria',
                        'productos.id_marca',
                        'productos.created_at as creado',
                        'productos.updated_at as actualizado',
                        'categorias.id as id_cat',
                        'categorias.nombre as categoria',
                        'marcas.id as id_mar',
                        'marcas.nombre as marca',
                    )->orderBy('productos.id', 'desc')->paginate(12);
            }


            $categorias = Categoria::all();
            $marcas = Marca::all();
            // $pathname = $request->path();
            $respuesta =  $this->data->respuesta;
            $menuSuperior = $this->data->menuSuperior;
            return view('admin.productos.lista', compact('productos', 'menuSuperior', 'request', 'respuesta', 'categorias', 'marcas'));
        } catch (\Throwable $th) {
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $mensaje = "Error del servidor. Erro: {$th->getMessage()}";
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /** FORMULARIO EDITAR PRODUCTO */
    public function formularioEditarProducto($codigo)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $utilidades = $this->data->utilidades;
            $inventarioPorDefecto = ['costo' => 0, 'cantidad' => 0, 'pvp' => 0];
            // return Producto::paginate(15);
            $productos = Helpers::setNameElementId(Producto::where('codigo', $codigo)->get(), 'id,nombre', 'categorias,marcas');

            foreach ($productos as $key => $producto) {
                $resultado = Inventario::where('codigo', $producto->codigo)->select('costo', 'cantidad', 'pvp')->get();
                $producto['inventario'] = count($resultado) ? $resultado[0] :  $inventarioPorDefecto;
            }

            $pathname = Request::path();
            $categorias = Categoria::all();
            $marcas = Marca::all();

            return view('admin.productos.formulario', compact('producto', 'categorias', 'marcas', 'menuSuperior', 'pathname', 'utilidades'));
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductoRequest $request)
    {
        try {

            $existeElCodigo = Producto::where('codigo', $request->codigo)->get();
            if (count($existeElCodigo)) {
                $mensaje = "¡El producto no se pudo crear porque el código ya existe!";
                $estatus = Response::HTTP_UNAUTHORIZED;
                return  back()->with(compact('mensaje', 'estatus'));
            }
            // Seteamos la imagen
            if ($request->file) {
                $url = Helpers::setFile($request);
                $request['imagen'] = $url;
            } else {
                $request['imagen'] = $this->data->datosDefault['FOTO_PORDEFECTO_PRODUCTO'];
            }

            // Validamos si el producto tiene stock inicial
            if ($request->cantidad_inicial > 0) {
                if ($request->costo > 0 && $request->pvp > 0) {
                    // Procesar una entrada sin facturar
                    Inventario::create([
                        "codigo" => $request->codigo,
                        "descripcion" => $request->descripcion,
                        "id_marca" => $request->id_marca,
                        "id_categoria" => $request->id_categoria,
                        "cantidad" => $request->cantidad_inicial,
                        "costo" => $request->costo,
                        "pvp" => $request->pvp,
                        "pvp_2" => 0,
                        "pvp_3" => 0,
                        "imagen" => $request->imagen,
                        "fecha_entrada" => date('Y-m-d'),
                    ]);
                } else {
                    $mensaje = "Se requiere del campo costo y PVP para procesar el registro";
                    $estatus = Response::HTTP_UNAUTHORIZED;
                    return back()->with(compact('mensaje', 'estatus'));
                }
            }

            // Registramos el producto
            $estatusCrear = Producto::create([
                "codigo" => $request->codigo,
                "descripcion" => $request->descripcion,
                "imagen" => $request->imagen,
                "id_marca" => $request->id_marca,
                "id_categoria" => $request->id_categoria,
                "fecha_vencimiento" => $request->fecha_vencimiento,
            ]);

            $mensaje = $estatusCrear ? "El producto se creó correctamente." : "El producto no se creo";
            $estatus = $estatusCrear ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(
                $request,
                $estatus,
                "Acción de Crear Producto, mensaje: ({$mensaje})"
            );

            return back()->with( compact('mensaje', 'estatus') );

        } catch (\Throwable $th) {
            $mensaje = "Error interno. Error: {$th->getMessage()}";
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with( compact('mensaje', 'estatus') );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductoRequest  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try {
            $estatusActualizar = false;

            // Validamos si el codigo no exista
            if ($request->codigo != $producto->codigo) {
                $existeElCodigo = Producto::where('codigo', $request->codigo)->get();
                if (count($existeElCodigo)) {
                    $estatus = 401;
                    $mensaje = "¡El producto no se pudo Editar por que el código que ingresaste ya lo tiene otro producto!, por favor intente con otro código";

                    return  back()->with(compact('mensaje', 'estatus'));
                }
            }

            // Seteamos la imagen
            if ($request->file) {
                // Removemos la imagen anterior
                if ($producto->imagen != $this->data->datosDefault['FOTO_PORDEFECTO_PRODUCTO']) {
                    Helpers::removeFile($producto->imagen);
                }
                $url = Helpers::setFile($request);
                $request['imagen'] = $url;
            } else {
                $request['imagen'] = $producto->imagen;
            }

            $estatusActualizar = $producto->update($request->all());

            $mensaje = $estatusActualizar ? "El producto se Actualizó correctamente." : "El producto No se Actualizo";
            $estatus = $estatusActualizar ? Response::HTTP_OK : Response::HTTP_UNAUTHORIZED;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(
                $request,
                $estatus,
                "Acción de Editar Producto, mensaje: ({$mensaje})"
            );

            return back()->with( compact('mensaje', 'estatus') );

        } catch (\Throwable $th) {
            $mensaje = "Error interno. Error: {$th->getMessage()}";
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }


    /** API REST FULL */
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        try {
            $mensaje = "";
            $estatus = "";
            $productoEnInventario = Inventario::where("codigo", $producto->codigo)->get();
            if (count($productoEnInventario)) {
                $mensaje = "El Producto {$producto->descripcion} tiene inventario no puede ser eliminado. para eliminar el producto debes eliminar el producto del inventario primero.";
                $estatus = Response::HTTP_UNAUTHORIZED;
            } else {
                $producto->delete();
                $mensaje = "El Producto se Eliminó correctamente.";
                $estatus = Response::HTTP_OK;
            }

            return back()->with(compact(
                "mensaje",
                "estatus"
            ));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error de al intentar Eliminar un nivel,");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact(
                "mensaje",
                "estatus"
            ));
        }
    }

    /** 
     * Esta funcion filtra los producto por descripcion y codigo de barra
     * si consigue el producto por codigo de barra primero solo retorna ese producto 1
     * de lo contrario retorna los producto por descripcion
     * 
     * @param resquest[ filtro, campo ]
     * @param filtro (contiene el argumento de tipo OBJECT descripcion o codigo)
     * @param campo (es un atributodel filtro de argumento de tipo array con las columna por donde se va a comprar el argumento)
     * @param ordenDelArra CAMPO [codigo, descripcion]
     * 
     */
    public function getProductosFiltro(HttpRequest $request)
    {
        try {

            $numeroDePagina = 15;
            if (isset($request->numeroDePagina)) {
                $numeroDePagina = $request->numeroDePagina;
            }

            $tasa = Utilidade::all()[0]->tasa;
            // filtramos por la descripcion
            if (request('filtro')) {
                /** Buscamos por codigo de barra */
                foreach ($request->campo as $key => $campo) {
                    switch ($campo) {
                        case 'codigo':
                            $resultados = Producto::where("{$campo}", $request->filtro)->get();

                            /** añadimos los datos de inventario */
                            foreach ($resultados as $key => $producto) {
                                $datosDeInvenario = Inventario::where('codigo', $producto->codigo)->get();
                                if (count($datosDeInvenario)) $producto['inventario'] =  $datosDeInvenario;
                                else $producto['inventario'] =  [];
                            }

                            $resultados =   Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                            if (count($resultados)) {
                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIPCION",
                                    "data" =>  [
                                        "data" => $resultados,
                                        "total" => count($resultados)
                                    ],
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }
                            break;
                        case 'descripcion':

                            if ($request->id_categoria > 0 && $request->id_marca > 0) {

                                $resultados = Producto::where([
                                    "id_categoria" => $request->id_categoria,
                                    "id_marca" => $request->id_marca
                                ])
                                    ->where("{$campo}", "like", "%{$request->filtro}%")
                                    ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                            } elseif ($request->id_categoria > 0) {

                                $resultados = Producto::where([
                                    "id_categoria" => $request->id_categoria,
                                ])
                                    ->where("{$campo}", "like", "%{$request->filtro}%")
                                    ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                            } elseif ($request->id_marca > 0) {
                                $resultados = Producto::where([
                                    "id_marca" => $request->id_marca
                                ])
                                    ->where("{$campo}", "like", "%{$request->filtro}%")
                                    ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                            } else {
                                $resultados = Producto::where("{$campo}", 'like', "%{$request->filtro}%")->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                            }

                            /** añadimos los datos de inventario */
                            foreach ($resultados as $key => $producto) {
                                $datosDeInvenario = Inventario::where('codigo', $producto->codigo)->get();
                                if (count($datosDeInvenario)) $producto['inventario'] =  $datosDeInvenario;
                                else $producto['inventario'] =  [];
                            }

                            /** Obtenemos los datos de marca y de categorias de cada producto */
                            $resultados = Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');


                            if (count($resultados)) {

                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                                    "data" => $resultados,
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            } else {
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
                                "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE, NO EXISTE ESTE PRODUCTO EN LA LISTA DE PRODUCTOS.",
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
            } else {
                if ($request->id_categoria > 0) {
                    $resultados = Producto::where([
                        "id_categoria" => $request->id_categoria,
                    ])
                        ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                } elseif ($request->id_marca > 0) {
                    $resultados = Producto::where([
                        "id_marca" => $request->id_marca,
                    ])
                        ->orderBy('descripcion', 'asc')->paginate($numeroDePagina);
                } else {
                    return response()->json([
                        "mensaje" => "El filtro no poseé parametros de busquedas",
                        "data" => ["data" => []],
                        "estatus" => Response::HTTP_NOT_FOUND
                    ], Response::HTTP_NOT_FOUND);
                }

                /** añadimos los datos de inventario */
                foreach ($resultados as $key => $producto) {
                    $datosDeInvenario = Inventario::where('codigo', $producto->codigo)->get();
                    if (count($datosDeInvenario)) $producto['inventario'] =  $datosDeInvenario;
                    else $producto['inventario'] =  [];
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
                "data" =>  ["data" => []],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * OBTIENE TODOS LOS PRODUCTOS
     * @return @return \Illuminate\Http\Response
     */
    public function getProductos()
    {
        try {
            $resultados = Producto::where('id', '>', 0)->orderBy('descripcion', 'asc')->paginate(15);
            if (count($resultados)) {
                $productos = Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                return response()->json([
                    "mensaje" => "Consulta de productos Exitosa",
                    "data" =>  $productos,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "mensaje" => "No hay resultados",
                    "data" =>  [],
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar productos");
            return response()->json([
                "mensaje" => $mensaje,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**  CIERRE API REST FULL */
}

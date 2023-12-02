<?php

namespace App\Http\Controllers;

use App\Models\{
    Categoria,
    DataDev,
    Producto,
    Helpers,
    Inventario,
    Marca
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menuSuperior = $this->data->menuSuperior;
        $utilidades = $this->data->utilidades;
        $pathname = Request::path();
        $categorias = Categoria::all();
        $marcas = Marca::all();
        return view('admin.productos.lista', compact( 'menuSuperior', 'pathname', 'utilidades', 'categorias', 'marcas') );
    }

    /** API REST FULL */
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
            $tasa = $this->data->utilidades[0]->tasa;

            // filtramos por la descripcion
            if (request('filtro')) {
                /** Buscamos por codigo de barra y descripcion */
                foreach ($request->campo as $key => $campo) {
                    switch ($campo) {
                        case 'codigo':
                            $resultados = Producto::where("{$campo}", $request->filtro)->get();
                            $resultados =   Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                            /** Le agregamos la informacion de STOCK al producto */
                            foreach ($resultados as $key => $producto) {
                                if( count(Inventario::where('codigo', $producto->codigo)->get()) ) {
                                    $producto['inventario'] = Inventario::where('codigo', $producto->codigo)->get();
                                }else{
                                    $producto['inventario'] = [];
                                }
                            }

                            if (count($resultados)) {
                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
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
                            $resultados = Producto::where("{$campo}", 'like', "%{$request->filtro}%")->orderBy('id', 'desc')->get();
                            $resultados =   Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
                            
                            /** Le agregamos la informacion de STOCK al producto */
                            foreach ($resultados as $key => $producto) {
                                if( count(Inventario::where('codigo', $producto->codigo)->get()) ) {
                                    $producto['inventario'] = Inventario::where('codigo', $producto->codigo)->get();
                                }else{
                                    $producto['inventario'] = [];
                                }
                            }
                            
                            if (count($resultados)) {

                                return response()->json([
                                    "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR DESCRIOCION",
                                    "data" => [
                                        "data" => $resultados,
                                        "total" => count($resultados)
                                    ],
                                    "tasa" => $tasa,
                                    "estatus" => Response::HTTP_OK
                                ], Response::HTTP_OK);
                            }
                            break;

                        default:
                            return response()->json([
                                "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE, NO HAY EXISTE ESTE PRODUCTO EN EL INVENTARIO.",
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
            }
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "ERROR AL FILTRAR LA BUSQUEDA DEL PRODUCTO,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProductos(){
        try {
            $resultados = Producto::where('id', '>', 0)->orderBy('id','desc')->paginate(15);
           
            $productos = Helpers::setNameElementId($resultados, 'id,nombre', 'categorias,marcas');
            
            if($productos){
                return response()->json([
                    "mensaje" => "Consulta de productos Exitosa",
                    "data"=>  $productos, 
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    "mensaje" => "No hay resultados",
                    "data"=>  $productos, 
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
            


        } catch (\Throwable $th) {
           $mensaje = Helpers::getMensajeError($th, "Error al consultar productos");
            return response()->json([
                "mensaje" => $mensaje,
                "data"=> [], 
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**  CIERRE API REST FULL */

   
    /** FORMULARIO EDITAR PRODUCTO */
    public function formularioEditarProducto($codigo){
        try {
            $menuSuperior = $this->data->menuSuperior;
            $utilidades = $this->data->utilidades;
            $inventarioPorDefecto = ['costo'=>0,'cantidad'=>0, 'pvp'=>0];
            // return Producto::paginate(15);
            $productos = Helpers::setNameElementId(Producto::where('codigo', $codigo)->get(), 'id,nombre', 'categorias,marcas');
            
            foreach ($productos as $key => $producto) {
                $resultado = Inventario::where('codigo', $producto->codigo)->select('costo', 'cantidad', 'pvp')->get();
                $producto['inventario'] = count($resultado) ? $resultado[0] :  $inventarioPorDefecto;
            }
            
            $pathname = Request::path();
            $categorias = Categoria::all();
            $marcas = Marca::all();
        
        
            
            return view('admin.productos.formulario', compact('producto', 'categorias', 'marcas', 'menuSuperior', 'pathname','utilidades'));
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
            $menuSuperior = $this->data->menuSuperior;

           
            // Seteamos la imagen
            if($request->file){
                $url = Helpers::setFile($request);
                $request['imagen'] = $url;
            }else {
                $request['imagen'] = $this->data->datosDefault['FOTO_PORDEFECTO_PRODUCTO'];
            }
           
           
            // Validamos si el producto tiene stock inicial
            if ($request->cantidad_inicial > 0) {
                if ($request->costo > 0) {
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
                }else{
                    $mensaje = "Se requiere del campo costo para procesar el registro";
                    $estatus = Response::HTTP_UNAUTHORIZED;
                    return redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') );
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
            $mensaje = $estatusCrear ? "El producto se creo correctamente." : "El producto no se creo";
            $estatus = $estatusCrear ? 201 : 404;
            
            return redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') );
            
        } catch (\Throwable $th) {
            //throw $th;
            $mensaje = "¡El producto no se pudo crear por que el código ya existe!";
            $estatus = 404;
            return redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') );

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
            $menuSuperior = $this->data->menuSuperior;
            $productos = Producto::all();
            $categorias = Categoria::all();
            $marcas = Marca::all();
            $pathname = '';
    
           
             // Seteamos la imagen
             if($request->file){
                // Removemos la imagen anterior
                if($producto->imagen != $this->data->datosDefault['FOTO_PORDEFECTO_PRODUCTO'] ){
                    Helpers::removeFile($producto->imagen);
                }
                $url = Helpers::setFile($request);
                $request['imagen'] = $url;
             }
            
             // Capturamos el codigo en caso de ser diferente
            $codigo = $producto->codigo;
             
            // Actualizar el producto
            $estatusActualizar = $producto->update($request->all());
    
            
            if ($estatusActualizar) {
                if($request->cantidad_inicial > 0){
                    if($request->cantidad_inicial > 0 && $request->costo > 0 && $request->pvp > 0){
                        // Procesar una entrada sin facturar
                        Inventario::updateOrCreate(
                            [
                                "codigo" => $codigo,
                            ],
                            [
                                "descripcion" => $producto->descripcion,
                                "id_marca" => $producto->id_marca,
                                "id_categoria" => $producto->id_categoria,
                                "imagen" => $producto->imagen,
                                "cantidad" => $request->cantidad_inicial ?? 0,
                                "costo" =>$request->costo ?? 0,
                                "pvp" => $request->pvp ?? 0,
                                "pvp_2" =>  0,
                                "pvp_3" =>  0
                        ]);
                    }else{
                        $estatus = 401;
                        $mensaje = "Si ingresas la cantidad debes ingresar COSTO Y PVP del producto";
                        return view('admin.productos.formulario', 
                        compact(
                            'producto', 'categorias', 'marcas', 'menuSuperior', 'pathname',
                            'mensaje', 'estatus'
                        ));
                    }
                }else{
                    $estaEnInventario = Inventario::where('codigo', $codigo)->get();
                    if(count($estaEnInventario)){
                        Inventario::updateOrCreate(
                            [
                                "codigo" => $codigo,
                            ],
                            [
                                "descripcion" => $producto->descripcion,
                                "id_marca" => $producto->id_marca,
                                "id_categoria" => $producto->id_categoria,
                                "imagen" => $producto->imagen,
                        ]);
                    }
                }
            }
            
            $mensaje = $estatusActualizar ? "El producto se Actualizó correctamente." : "El producto No se Actualizo";
            $estatus = $estatusActualizar ? 201 : 404;
            
            return $estatusActualizar ? redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') )
            : view('admin.productos.lista', compact('mensaje', 'estatus', 'menuSuperior', 'request', 'categorias', 'marcas', 'productos'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al actualizar el producto, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        try {
            $productoEnInventario = Inventario::where("codigo", $producto->codigo)->get();
            if(count($productoEnInventario)){
                $mensaje = "El Producto Tiene inventario no puede ser eliminado.";
                $estatus = Response::HTTP_UNAUTHORIZED;
            }else{
                $producto->delete();
                $mensaje = "El Producto se Eliminó correctamente.";
                $estatus = Response::HTTP_OK;
            }

            return response()->json([
                "mensaje" => $mensaje,
                "data" => $producto,
                "estatus" => $estatus
            ], $estatus);

        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de al intentar Eliminar un nivel,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

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
use Illuminate\Http\Response;
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
        
        $productos = Helpers::setNameElementId(Producto::where('id', '>', 0)->orderBy('id', 'desc')->get( ), 'id,nombre', 'categorias,marcas');
 
        $pathname = Request::path();
        $categorias = Categoria::all();
        $marcas = Marca::all();
        return view('admin.productos.lista', compact('productos', 'menuSuperior', 'categorias', 'marcas', 'pathname', 'utilidades') );
    }

    public function getProductos(){
        try {
            $products = Producto::where("id",">=", 1)->OrderBy('id', 'desc')->simplePaginate(5);
            $data = json_decode(json_encode($products));

            // $data->data = Helpers::setNameElementId($data->data, ['id','name'], ['marcas', 'categorias']);
        
            
            return response()->json([
                "message" => count($data->data) ? "Consulta Exitosa" : "No hay Datos",
                "data"=> $data, 
                "estatus" => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message" => "Error en el consultar productos, " . $th->getMessage(),
                "data"=> [], 
                "estatus" => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
       
    }

    public function getProductoData($barcode){
        try {
            $utilidades = $this->data->utilidades;
            $product = Producto::where(['codigo'=> $barcode])->get();
            if (count($product)) {
                return response()->json([
                    "message" => "Consulta exitosa",
                    "data" => $product[0],
                    "status" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    "message" => "No hay resultados",
                    "data" => [],
                    "status" => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error al consultar producto por codigo de barra, " . $th->getMessage(),
                "data" => [],
                "status" => 500
            ], 500);
        }
    }


    public function getProducto($barcode){
        try {
            // $utilidades = $this->data->utilidades;
            $product = Inventario::where(['codigo'=> $barcode])->get();
            // return $utilidades->pvp_1['sumar'];
            // if($product[0]->pvp == 0){
            //     $product[0]->pvp =  $product[0]->costo * $utilidades->pvp_1['sumar'];
            // }
            if (count($product)) {
                return response()->json([
                    "message" => "Consulta exitosa",
                    "data" => $product[0],
                    "status" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    "message" => "El código de barra no corresponde a ningún producto en nuestros registros, por favor ingrese otro código o registre el producto.",
                    "data" => [],
                    "status" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error al consultar producto por codigo de barra, " . $th->getMessage(),
                "data" => [],
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
     * @param  \App\Http\Requests\StoreProductoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            // return $request;
            $menuSuperior = $this->data->menuSuperior;
            $productos = Producto::all();
            $categorias = Categoria::all();
            $marcas = Marca::all();
            // Seteamos la imagen
            if($request->file){
                $url = Helpers::setFile($request);
                $request['imagen'] = $url;
            }else {
                $request['imagen'] = FOTO_PORDEFECTO_PRODUCTO;
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
                        "fecha_entrada" => date('Y-m-d'),
                        // "ultimo_costo" => $request->ultimo_costo,
                        // "cantidad_ultimo_costo" => $request->cantidad_ultimo_costo,
                        // "utilidad_personalizada" => $request->utilidad_personalizada,
                        // "fecha_vencimiento" => $request->fecha_vencimiento,
                    ]);
                }else{
                    $mensaje = [
                        "texto" => "Se requiere del campo costo para procesar el registro",
                        "input" => "costo"
                    ];
                    return view('admin.productos.lista', compact('request', 'mensaje', 'menuSuperior','productos', 'categorias', 'marcas'));
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
                // "cantidad_inicial" => $request->cantidad_inicial,
                // "costo" => $request->costo,
                // "utilidad_personalizada" => $request->utilidad_personalizada,
            ]);
            
            $mensaje = $estatusCrear ? "El producto se creo correctamente." : "El producto no se creo";
            $estatus = $estatusCrear ? 201 : 404;
            
            return $estatusCrear ? redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') )
            : view('admin.productos.lista', compact('mensaje', 'estatus', 'menuSuperior', $request));
            
        } catch (\Throwable $th) {
            //throw $th;
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear el producto, ");
            return view('errors.404', compact('mensajeError'));

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
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
        $menuSuperior = $this->data->menuSuperior;
        $productos = Producto::all();
        $categorias = Categoria::all();
        $marcas = Marca::all();

        // Actualizar el producto
        $estatusActualizar = $producto->update($request->all());
        
        $mensaje = $estatusActualizar ? "El producto se Actualizó correctamente." : "El producto No se Actualizo";
        $estatus = $estatusActualizar ? 201 : 404;
        
        return $estatusActualizar ? redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') )
        : view('admin.productos.lista', compact('mensaje', 'estatus', 'menuSuperior', 'request', 'categorias', 'marcas', 'productos'));
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
                $estatus = 401;
                return redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') );
            }

            $producto->delete();
            $mensaje = "El Producto se Eliminó correctamente.";
            $estatus = 200;
            return redirect()->route( 'admin.productos.index', compact('mensaje', 'estatus') );
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de al intentar Eliminar un nivel,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }
}

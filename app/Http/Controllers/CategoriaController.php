<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Monitore;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CategoriaController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }

    /** API REST FULL CATEGORIAS */
    public function getCategorias(){
        try {
            $resultados = Categoria::all();

            if(count($resultados)){
                $mensaje = "Consula de categorias exitosa.";
                $estatus = Response::HTTP_OK;
            }else{
                $mensaje = "No hay categorias registradas.";
                $estatus = Response::HTTP_OK;
            }

            return response()->json([
                "mensaje" => $mensaje,
                "data" => $resultados,
                "estatus" => $estatus
            ], $estatus);

        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar las categorias");
            return response()->json([
                "mensaje" => $mensaje,
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
            $categorias = Categoria::orderBy('nombre', 'asc')->get();
            $menuSuperior = $this->data->menuSuperior;
            return view('admin.categorias.lista', compact('menuSuperior', 'categorias'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar las categorias, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoriaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriaRequest $request)
    {
        try {  
            $estatusCrear = Categoria::create($request->all());
    
            $mensaje = $estatusCrear ? "La categoria se creo correctamente." : "La categoria no se creo";
            $estatus = $estatusCrear ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario($request, $estatus,"Acción de crear categoria ({$request->nombre})");
            
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return $estatusCrear ? redirect()->route( 'admin.categorias.index', compact('mensaje', 'estatus') )
            : view('admin.categorias.lista', compact('mensaje', 'estatus', 'menuSuperior','pathname', $request));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear la categoria, ");
            return view('errors.404', compact('mensajeError'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoriaRequest  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        try {
            $estatusActualizar = $categoria->update($request->all());
    
            $mensaje = $estatusActualizar ? "La categoria se actualizó correctamente." : "Los cambios no se guardaron!";
            $estatus = $estatusActualizar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario($request,$estatus,"Acción de actualizar categoria ({$request->nombre})");
            
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return $estatusActualizar ? redirect()->route( 'admin.categorias.index', compact('mensaje', 'estatus') )
            : view('admin.categorias.lista', compact('mensaje', 'estatus', 'menuSuperior', 'pathname',$request));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Actualizar la categoria, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        try {

            $categoriaEnUso = Producto::where("id_categoria", $categoria->id)->get();
            if(count($categoriaEnUso)){
                $mensaje = "La categoria esta en uso y no puede ser eliminada.";
                $estatus = 401;
                return redirect()->route( 'admin.categorias.index', compact('mensaje', 'estatus') );
            }

            $estatusEliminar = $categoria->delete();
    
            $mensaje = $estatusEliminar ? "La categoria se eliminó correctamente." : "No se pudo eliminar la catgoria";
            $estatus = $estatusEliminar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(request(), $estatus,"Acción de eliminar categoria ({$categoria->nombre})");

            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return redirect()->route( 'admin.categorias.index', compact('mensaje', 'estatus') );

        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Eliminar la categoria, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Producto;
use Illuminate\Support\Facades\Request;

class CategoriaController extends Controller
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
        try {
            $categorias = Categoria::all();
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return view('admin.categorias.lista', compact('menuSuperior', 'categorias', 'pathname'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar las categorias, ");
            return view('errors.404', compact('mensajeError'));
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
     * @param  \App\Http\Requests\StoreCategoriaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriaRequest $request)
    {
        try {
            $estatusCrear = Categoria::create($request->all());
    
            $mensaje = $estatusCrear ? "La categoria se creo correctamente." : "La categoria no se creo";
            $estatus = $estatusCrear ? 201 : 404;
            
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
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        //
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
            $estatus = $estatusActualizar ? 201 : 404;
            
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
            $estatus = $estatusEliminar ? 201 : 404;
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return $estatusEliminar ? redirect()->route( 'admin.categorias.index', compact('mensaje', 'estatus') )
            : view('admin.categorias.lista', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', $categoria));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Eliminar la categoria, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
}

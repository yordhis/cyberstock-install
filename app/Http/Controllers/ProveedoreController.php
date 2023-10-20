<?php

namespace App\Http\Controllers;

use App\Models\{
    DataDev,
    Helpers,
    Proveedore
};
use App\Http\Requests\StoreProveedoreRequest;
use App\Http\Requests\UpdateProveedoreRequest;
use Illuminate\Support\Facades\Request;

class ProveedoreController extends Controller
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
        $proveedores = Proveedore::all();
        $pathname  = Request::path();
        $menuSuperior = $this->data->menuSuperior;
        return view('admin.proveedores.lista', compact('menuSuperior', 'proveedores', 'pathname'));
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
     * @param  \App\Http\Requests\StoreProveedoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProveedoreRequest $request)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $resulProveedor = Proveedore::where('codigo', $request->codigo)->get();
            // Validamos si ya existe el proveedor
            if(count($resulProveedor)){
                $proveedores = Proveedore::all();
                $this->data->respuesta['mensaje'] = "El Código o Número de documento del proveedor ¡Ya existe! verifique los datos y vuelva a intentar";
                $this->data->respuesta['estatus'] = 404;
                $this->data->respuesta['activo'] = true;
                $this->data->respuesta['elemento'] = "modalCrearProveedor";
                $respuesta =  $this->data->respuesta;
                return view('admin.proveedores.lista', compact('proveedores', 'menuSuperior', 'request', 'respuesta'));
            }
            // Seteamos la imagen
            if($request->file){
                $url = Helpers::setFile($request);
                $request['imagen'] = $url;
            }else {
                $request['imagen'] = FOTO_PORDEFECTO;
            }

            $estatusCrear = Proveedore::create($request->all());

            $mensaje = $estatusCrear ? "Proveedor registrado correctamente" : "No se registro el proveedor";
            $estatus = $estatusCrear ? 201 : 404;

     
            if($request->formulario == "inventarios/crearEntrada"){
                return redirect()->route('admin.inventarios.crearEntrada', compact('mensaje', 'estatus'));
            }else{
                return redirect()->route("admin.proveedores.index", compact('mensaje', 'estatus'));
            }

        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear el Proveedor, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedore $proveedore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedore $proveedore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProveedoreRequest  $request
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProveedoreRequest $request, Proveedore $proveedore)
    {
        try {
            $estatusActualizar = $proveedore->update($request->all());
            $mensaje = $estatusActualizar ? "Proveedor Se actualizó correctamente" : "No se pudieron guardar los cambios del proveedor";
            $estatus = $estatusActualizar ? 200 : 404;
       
            return redirect()->route('admin.proveedores.index', compact('mensaje', 'estatus'));
            
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear el producto, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedore $proveedore)
    {
        $estatusEliminar = $proveedore->delete();

        $mensaje = $estatusEliminar ? "Proveedor Se Eliminó correctamente" : "No se pudo eliminar el proveedor";
        $estatus = $estatusEliminar ? 200 : 404;

        return redirect()->route('admin.proveedores.index', compact('mensaje', 'estatus')); 
    }
}

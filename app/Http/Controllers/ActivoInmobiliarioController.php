<?php

namespace App\Http\Controllers;

use App\Models\ActivoInmobiliario;
use App\Http\Requests\StoreActivoInmobiliarioRequest;
use App\Http\Requests\UpdateActivoInmobiliarioRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivoInmobiliarioController extends Controller
{
    public $data;
    public function __construct(){$this->data = new DataDev;}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activoInmobiliarios = ActivoInmobiliario::orderBy('created_at', 'desc')->paginate(10);
        $menuSuperior = [];
        return view('admin.activoInmobiliarios.lista', compact('activoInmobiliarios', 'menuSuperior'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreActivoInmobiliarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivoInmobiliarioRequest $request)
    {
        
        try {  

            $request['estatus'] = isset($request->estatus) ? $request->estatus : 0; 

            $estatusCrear = ActivoInmobiliario::create($request->all());
    
             $this->data->respuesta['mensaje'] = $mensaje = $estatusCrear ? "El activo se registro correctamente." : "El activo no se registro";
             $this->data->respuesta['estatus'] = $estatus = $estatusCrear ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario($request, $estatus,"Acción de registrar activo ({$request->descripcion})");
            $respuesta = $this->data->respuesta;

            $menuSuperior = [];
                    $activoInmobiliarios = ActivoInmobiliario::orderBy('created_at', 'desc')->paginate(10);
            $request = $estatusCrear ? [] : $request;
            return view('admin.activoInmobiliarios.lista', compact('respuesta', 'menuSuperior', 'request', 'activoInmobiliarios'));
        
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "No se pudo registrar el activo inmobiliario, Código ya existe,");
            $this->data->respuesta['mensaje']  = $mensajeError;
            $this->data->respuesta['estatus']  = Response::HTTP_NOT_FOUND;
            $respuesta = $this->data->respuesta;
            $menuSuperior = [];
            $activoInmobiliarios = ActivoInmobiliario::orderBy('created_at', 'desc')->paginate(10);
            return view('admin.activoInmobiliarios.lista', compact('respuesta', 'menuSuperior', 'request', 'activoInmobiliarios'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActivoInmobiliarioRequest  $request
     * @param  \App\Models\ActivoInmobiliario  $activoInmobiliario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActivoInmobiliarioRequest $request, ActivoInmobiliario $activoInmobiliario)
    {
        return $activoInmobiliario;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivoInmobiliario  $activoInmobiliario
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivoInmobiliario $activoInmobiliario, $id)
    {
        try {
            
            $activoInmobiliario = ActivoInmobiliario::find($id);
            $estatusEliminar = $activoInmobiliario->delete();
    
            $this->data->respuesta['mensaje']  = $mensaje = $estatusEliminar 
                                            ? "El activo inmobiliario se eliminó correctamente." 
                                            : "No se pudo eliminar activo inmobiliario";
            $this->data->respuesta['estatus']  = $estatus = $estatusEliminar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(request(), $estatus,"Acción de eliminar Activo Inmobiliario ({$activoInmobiliario->descripcion})");

            $respuesta = $this->data->respuesta;
            $menuSuperior = [];
            $activoInmobiliarios = ActivoInmobiliario::orderBy('created_at', 'desc')->paginate(10);
            return view( 'admin.activoInmobiliarios.lista', compact('activoInmobiliarios', 'respuesta', 'menuSuperior') );

        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "No se pudo eliminar el activo inmobiliario no existe en nuestro registro. ");
            $this->data->respuesta['mensaje']  = $mensajeError;
            $this->data->respuesta['estatus']  = Response::HTTP_NOT_FOUND;
            $respuesta = $this->data->respuesta;
            $menuSuperior = [];
            $activoInmobiliarios = ActivoInmobiliario::orderBy('created_at', 'desc')->paginate(10);
            return view('admin.activoInmobiliarios.lista', compact('respuesta', 'menuSuperior', 'activoInmobiliarios'));
        }
    }
}

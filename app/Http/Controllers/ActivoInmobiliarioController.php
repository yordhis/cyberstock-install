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
            $estatusCrear = ActivoInmobiliario::create($request->all());
    
             $this->data->respuesta['mensaje'] = $mensaje = $estatusCrear ? "El activo se registro correctamente." : "El activo no se registro";
             $this->data->respuesta['estatus'] = $estatus = $estatusCrear ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario($request, $estatus,"Acción de registrar activo ({$request->descripcion})");
            $respuesta = $this->data->respuesta;

            $menuSuperior = [];
            $activoInmobiliarios = ActivoInmobiliario::where('estatus', 1)->paginate(10);
            $request = $estatusCrear ? [] : $request;
            return view('admin.activoInmobiliarios.lista', compact('respuesta', 'menuSuperior', 'request', 'activoInmobiliarios'));
        
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al registrar el inmobiliario, ");
            $this->data->respuesta['mensaje']  = $mensajeError;
            $this->data->respuesta['estatus']  = Response::HTTP_NOT_FOUND;
            $respuesta = $this->data->respuesta;
            $request =  [];
            $menuSuperior = [];
            $activoInmobiliarios = ActivoInmobiliario::where('estatus', 1)->paginate(10);
            return view('admin.activoInmobiliarios.lista', compact('respuesta', 'menuSuperior', 'request', 'activoInmobiliarios'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActivoInmobiliario  $activoInmobiliario
     * @return \Illuminate\Http\Response
     */
    public function show(ActivoInmobiliario $activoInmobiliario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActivoInmobiliario  $activoInmobiliario
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivoInmobiliario $activoInmobiliario)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivoInmobiliario  $activoInmobiliario
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivoInmobiliario $activoInmobiliario)
    {
        //
    }
}

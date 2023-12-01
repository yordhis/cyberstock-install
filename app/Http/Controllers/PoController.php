<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePoRequest;
use App\Http\Requests\UpdatePoRequest;
use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\Factura;
use App\Models\Helpers;
use App\Models\Po;
use Illuminate\Support\Facades\Request;

class PoController extends Controller
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
            $menuSuperior = $this->data->menuSuperior;
            $pathname = Request::path();
           
            return view('admin.pos.index', compact('menuSuperior',  'pathname'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error al entrar al pos de venta index, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoRequest $request)
    {
        return redirect('/pos/1');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function show(Po $po)
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $pathname = Request::path();
            return view('admin.pos.empresa', compact('menuSuperior', 'pathname', 'po'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar POS, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePoRequest  $request
     * @param  \App\Models\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePoRequest $request, Po $po)
    {
        try {
            // return $po;

            if(isset($request->estatusImagen)){
                $request['estatusImagen'] = 1;
            }else{
                $request['estatusImagen'] = 0;
            }

            if($request->file){
                $url = Helpers::setFile($request);
                $request['imagen'] = $url;
            }else {
                $request['imagen'] = $po->imagen;
            }
           
            $id = $po->id;
            $estatusActualizar = $po->update($request->all());
          
            if($estatusActualizar == 0){
                if($url != null){
                    if ($url !=  $this->data->datosDefault['LOGO_PORDEFECTO']) {
                        Helpers::removeFile($url);
                    }
                }
            }
            
            $mensaje = $estatusActualizar ? "El POS se Actualizó correctamente." : "El POS No se Actualizo";
            $estatus = $estatusActualizar ? 201 : 404;
            return redirect()->route('admin.pos.show', [$id, "estatus" => $estatus, "mensaje"=> $mensaje]);
        } catch (\Throwable $th) {
            if($url != null){
                if ($url != $this->data->datosDefault['LOGO_PORDEFECTO']) {
                    Helpers::removeFile($url);
                }
            }
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar POS, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

}

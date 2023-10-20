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
            $codigo = Helpers::getCodigo('facturas');
            $carritos = Carrito::where('codigo', $codigo)->get();
            $pos = Po::all();

            
            
            if(count($this->data->utilidades)){
                $tasa = number_format(floatval($this->data->utilidades[0]->tasa), 2);   
                $iva = floatval($this->data->utilidades[0]->iva['iva']);
            }else{
                $tasa = 0;
                $iva = 0;
            }

            
            if (count($carritos)) {
                foreach ($carritos as $key => $producto) {
                    $producto['cliente'] = Cliente::where('identificacion', $producto->identificacion)->get()[0];
                    $producto->costoBs = number_format(floatval($producto->costo) * $tasa, 2, ',', '.') . " Bs";
                    $producto->subtotalBs = number_format(floatval($producto->subtotal) * $tasa, 2, ',', '.') . " Bs";
                }
            }
            
            return view('admin.pos.index', compact('menuSuperior', 'codigo', 'carritos', 'tasa', 'iva', 'pathname', 'pos'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error al entrar al pos de venta index, ");
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
     * @param  \App\Http\Requests\StorePoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoRequest $request)
    {
        return $request;
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function edit(Po $po)
    {
        return $po;
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
                    if ($url != LOGO_PORDEFECTO) {
                        Helpers::removeFile($url);
                    }
                }
            }
            
            $mensaje = $estatusActualizar ? "El POS se Actualizó correctamente." : "El POS No se Actualizo";
            $estatus = $estatusActualizar ? 201 : 404;
            return redirect()->route('admin.pos.show', [$id, "estatus" => $estatus, "mensaje"=> $mensaje]);
        } catch (\Throwable $th) {
            if($url != null){
                if ($url != LOGO_PORDEFECTO) {
                    Helpers::removeFile($url);
                }
            }
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar POS, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function destroy(Po $po)
    {
        //
    }
}

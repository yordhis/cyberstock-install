<?php

namespace App\Http\Controllers;

use App\Models\Utilidade;
use App\Http\Requests\StoreUtilidadeRequest;
use App\Http\Requests\UpdateUtilidadeRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use Illuminate\Support\Facades\Request;

class UtilidadeController extends Controller
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
        try {
            $utilidades = Utilidade::all();
            $menuSuperior = $this->data->menuSuperior;
            $pathname = Request::path();
            return view('admin.utilidades.index', compact('menuSuperior', 'pathname', 'utilidades'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar las utilidades del sistema, ");
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
     * @param  \App\Http\Requests\StoreUtilidadeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUtilidadeRequest $request)
    {
        try {
    
            // $id = explode("/",$request->path())[1];
            $estatusCrear = Utilidade::create($request->all());

            $mensaje = $estatusCrear ? "Las utilidades se Guardaron correctamente." : "Los cambios no se guardaron!";
            $estatus = $estatusCrear ? 201 : 404;
            
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return $estatusCrear ? redirect()->route( 'admin.utilidades.index', compact('mensaje', 'estatus') )
            : view('admin.utilidades.index', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', 'request'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Guardar la utilidades, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Utilidade  $utilidade
     * @return \Illuminate\Http\Response
     */
    public function show(Utilidade $utilidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Utilidade  $utilidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Utilidade $utilidade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUtilidadeRequest  $request
     * @param  \App\Models\Utilidade  $utilidade
     * @return \Illuminate\Http\Response
     */
    public function updateTasa($idUtil)
    {
        try {
            $nuevaTasa = $_POST['tasa'];
     
            // $id = explode("/",$request->path())[1];
            $estatusActualizar = Utilidade::where("id", $idUtil)->update(['tasa' => $nuevaTasa]);
     
            $mensaje = $estatusActualizar ? "Tasa se actualizó correctamente." : "Los cambios no se guardaron!";
            $estatus = $estatusActualizar ? 200 : 404;
            
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return redirect()->route( 'admin.pos.index', compact('mensaje', 'estatus') );
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Actualizar la utilidades, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
    public function update(UpdateUtilidadeRequest $request, Utilidade $utilidade)
    {
        try {
  
            // $id = explode("/",$request->path())[1];
            $estatusActualizar = $utilidade->update($request->all());

            $mensaje = $estatusActualizar ? "Las utilidades se actualizaron correctamente." : "Los cambios no se guardaron!";
            $estatus = $estatusActualizar ? 201 : 404;
            
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return $estatusActualizar ? redirect()->route( 'admin.utilidades.index', compact('mensaje', 'estatus') )
            : view('admin.utilidades.index', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', 'request', 'utilidades'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Actualizar la utilidades, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Utilidade  $utilidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Utilidade $utilidade)
    {
        //
    }
}

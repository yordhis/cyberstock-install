<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Producto;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class MarcaController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }

        /** API REST FULL MARCAS */
        public function getMarcas(){
            try {
                $resultados = Marca::all();

                if(count($resultados)){
                    $mensaje = "Consula de marcas exitosa.";
                    $estatus = Response::HTTP_OK;
                }else{
                    $mensaje = "No hay marcas registradas.";
                    $estatus = Response::HTTP_OK;
                }

                return response()->json([
                    "mensaje" => $mensaje,
                    "data" => $resultados,
                    "estatus" => $estatus
                ], $estatus);

            } catch (\Throwable $th) {
                $mensaje = Helpers::getMensajeError($th, "Error al consultar las marcas");
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
            $marcas = Marca::orderBy('nombre', 'asc')->paginate(10);
            $menuSuperior = $this->data->menuSuperior;
            


            return view('admin.marcas.lista', compact('menuSuperior', 'marcas') );
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar las marcas, ");
            return view('errors.404', compact('mensajeError'));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMarcaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarcaRequest $request)
    {
        try {
            $estatusCrear = Marca::create($request->all());
    
            $mensaje = $estatusCrear ? "La marca se creo correctamente." : "La marca no se creo";
            $estatus = $estatusCrear ? 201 : 404;
            
            return $estatusCrear ? redirect()->route( 'admin.marcas.index', compact('mensaje', 'estatus') )
            : view('admin.marcas.lista', compact('mensaje', 'estatus', 'menuSuperior', $request));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear la marca, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMarcaRequest  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarcaRequest $request, Marca $marca)
    {
        try {
            $estatusActualizar = $marca->update($request->all());
    
            $mensaje = $estatusActualizar ? "La marca se actualizó correctamente." : "La marca no se actualizó";
            $estatus = $estatusActualizar ? 201 : 404;
            
            return $estatusActualizar ? redirect()->route( 'admin.marcas.index', compact('mensaje', 'estatus') )
            : view('admin.marcas.lista', compact('mensaje', 'estatus', 'menuSuperior', $request));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al actualizar la marca, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marca $marca)
    {
        try {
            $marcaEnUso = Producto::where("id_marca", $marca->id)->get();
            if(count($marcaEnUso)){
                $mensaje = "La marca esta en uso y no puede ser eliminada.";
                $estatus = 401;
                return redirect()->route( 'admin.marcas.index', compact('mensaje', 'estatus') );
            }

            $estatusEliminar = $marca->delete();
    
            $mensaje = $estatusEliminar ? "La marca se Eliminó correctamente." : "La marca no se eliminó";
            $estatus = $estatusEliminar ? 201 : 404;
            
            return $estatusEliminar ? redirect()->route( 'admin.marcas.index', compact('mensaje', 'estatus') )
            : view('admin.marcas.lista', compact('mensaje', 'estatus', 'menuSuperior', $marca));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al eliminar la marca, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
}

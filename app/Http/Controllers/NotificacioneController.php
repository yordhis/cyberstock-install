<?php

namespace App\Http\Controllers;

use App\Models\Notificacione;
use App\Http\Requests\StoreNotificacioneRequest;
use App\Http\Requests\UpdateNotificacioneRequest;
use App\Models\Inventario;
use Illuminate\Http\Response;

class NotificacioneController extends Controller
{


    /**
     * 
     * API REST FULL
     */

     public function getNotificaciones(){
        $this->setNotificaciones();
        return Notificacione::where('estatus', false)->orderBy('id', 'desc')->paginate(3);
     }

     public function setNotificaciones(){
        $resultados = Inventario::cursor()->filter(function (Inventario $inventario){
            return $inventario->cantidad < 5;
        });

        foreach ($resultados as $key => $producto) {
            Notificacione::updateOrCreate(
                [
                    'codigo' => $producto->codigo,
                ],
                [
                    'codigo' => $producto->codigo,
                    'descripcion' => $producto->descripcion, 
                    'cantidad'=> $producto->cantidad, 
                    'almacen' => 1, 
                ]
            );
        }
     }

     /** CIERRE API REST FULL */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menuSuperior = [];
        $notificaciones = Notificacione::orderBy('id', 'desc')->paginate(15);
        return view('admin.notificaciones.index', compact('notificaciones','menuSuperior'));
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
     * @param  \App\Http\Requests\StoreNotificacioneRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotificacioneRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function show(Notificacione $notificacione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function edit(Notificacione $notificacione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNotificacioneRequest  $request
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotificacioneRequest $request, Notificacione $notificacione)
    {
        $mensaje = "";
        $estatus = 404;
        
        $resultado = $notificacione->update(["estatus" => $request->estatus]);
        $mensaje = $resultado ? "Se marco como visto." : "La notificación No se marco como visto.";
        $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
     
        return redirect()->route('admin.notificaciones.index', compact('mensaje', 'estatus'));
        
    }

    public function marcaComoLeidoAll()
    {
        $mensaje = "";
        $estatus = 404;

        $resultado = Notificacione::where('id', '>=', 0)->update(["estatus" => 1]);
        $mensaje = $resultado ? "Se marco como visto todas las notificaciones." : "La notificaciones No se marcaron como visto.";
        $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
     
        return redirect()->route('admin.notificaciones.index', compact('mensaje', 'estatus'));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notificacione $notificacione)
    {
        $mensaje = "";
        $estatus = 404;
        $resultado = $notificacione->delete();
        $mensaje = $resultado ? "Se eliminó la notificación." : "Fallo al eliminar la notificación.";
        $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
        return redirect()->route('admin.notificaciones.index', compact('mensaje', 'estatus'));
    }

    public function destroyAll()
    {
        $mensaje = "";
        $estatus = 404;
        $resultado = Notificacione::where('id', '>=', 0)->delete();
        $mensaje = $resultado ? "Se eliminaron todas las notificaciones." : "Fallo al eliminar toda las notificaciones.";
        $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
        return redirect()->route('admin.notificaciones.index', compact('mensaje', 'estatus'));
    }
}

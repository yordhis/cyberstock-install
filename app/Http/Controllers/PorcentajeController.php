<?php

namespace App\Http\Controllers;

use App\Models\{
    Porcentaje,
    Categoria,
    Inventario,
    Marca
};
use App\Http\Requests\StorePorcentajeRequest;
use App\Http\Requests\UpdatePorcentajeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PorcentajeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('admin.panel.index',[
            "mensaje" => "Modulo no disponible, si desea este módulo debe contactar soporte y solicitar la activación",
            "estatus" => 301
        ]);
        $menuSuperior = [];
        $categorias = Categoria::all();
        $marcas = Marca::all();

        return view('admin.porcentajes.lista', compact('menuSuperior', 'marcas', 'categorias'));
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
     * @param  \App\Http\Requests\StorePorcentajeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePorcentajeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Porcentaje  $porcentaje
     * @return \Illuminate\Http\Response
     */
    public function show(Porcentaje $porcentaje)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Porcentaje  $porcentaje
     * @return \Illuminate\Http\Response
     */
    public function edit(Porcentaje $porcentaje)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePorcentajeRequest  $request
     * @param  \App\Models\Porcentaje  $porcentaje
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $resultado = Inventario::where('id', $request->id)->update([
            "costo" => number_format($request->costo_despues,2),
            "pvp" => number_format($request->pvp_despues, 2),
            "pvp_2" => number_format($request->pvp_2_despues, 2),
            "pvp_3" => number_format($request->pvp_3_despues, 2)
        ]);
    
        if($resultado){
            return response()->json([
                "mensaje" => "Precios y costo actualizado con exito.",
                "data" => $request->all(),
                "estatus" => Response::HTTP_OK
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                "mensaje" => "Fallo al actualizar los precio y costo.",
                "data" => $request->all(),
                "estatus" => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Porcentaje  $porcentaje
     * @return \Illuminate\Http\Response
     */
    public function destroy(Porcentaje $porcentaje)
    {
        //
    }
}

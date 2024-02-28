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
            "costo" => $request->costo_despues,
            "pvp" => $request->pvp_despues,
            "pvp_2" => $request->pvp_2_despues,
            "pvp_3" => $request->pvp_3_despues
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

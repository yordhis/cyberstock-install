<?php

namespace App\Http\Controllers;

use App\Models\Monitore;
use App\Http\Requests\StoreMonitoreRequest;
use App\Http\Requests\UpdateMonitoreRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MonitoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreMonitoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $resultado = Monitore::create($request->all());
            return response()->json([
                "mensaje" => "Movimiento registrado correctamente.",
                "data" => $resultado,
                "estatus" => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {

            return response()->json([
                "mensaje" => "Todos los campos son requeridos.",
                "data" => null,
                "estatus" => Response::HTTP_UNAUTHORIZED
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Monitore  $monitore
     * @return \Illuminate\Http\Response
     */
    public function show(Monitore $monitore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Monitore  $monitore
     * @return \Illuminate\Http\Response
     */
    public function edit(Monitore $monitore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMonitoreRequest  $request
     * @param  \App\Models\Monitore  $monitore
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMonitoreRequest $request, Monitore $monitore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Monitore  $monitore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Monitore $monitore)
    {
        //
    }
}

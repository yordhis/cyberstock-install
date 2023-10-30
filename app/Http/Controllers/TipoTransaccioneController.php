<?php

namespace App\Http\Controllers;

use App\Models\TipoTransaccione;
use App\Http\Requests\StoreTipoTransaccioneRequest;
use App\Http\Requests\UpdateTipoTransaccioneRequest;

class TipoTransaccioneController extends Controller
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
     * @param  \App\Http\Requests\StoreTipoTransaccioneRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipoTransaccioneRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoTransaccione  $tipoTransaccione
     * @return \Illuminate\Http\Response
     */
    public function show(TipoTransaccione $tipoTransaccione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoTransaccione  $tipoTransaccione
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoTransaccione $tipoTransaccione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTipoTransaccioneRequest  $request
     * @param  \App\Models\TipoTransaccione  $tipoTransaccione
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTipoTransaccioneRequest $request, TipoTransaccione $tipoTransaccione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoTransaccione  $tipoTransaccione
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoTransaccione $tipoTransaccione)
    {
        //
    }
}

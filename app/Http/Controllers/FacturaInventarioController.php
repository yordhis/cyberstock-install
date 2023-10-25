<?php

namespace App\Http\Controllers;

use App\Models\FacturaInventario;
use App\Http\Requests\StoreFacturaInventarioRequest;
use App\Http\Requests\UpdateFacturaInventarioRequest;

class FacturaInventarioController extends Controller
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
     * @param  \App\Http\Requests\StoreFacturaInventarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFacturaInventarioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FacturaInventario  $facturaInventario
     * @return \Illuminate\Http\Response
     */
    public function show(FacturaInventario $facturaInventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FacturaInventario  $facturaInventario
     * @return \Illuminate\Http\Response
     */
    public function edit(FacturaInventario $facturaInventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFacturaInventarioRequest  $request
     * @param  \App\Models\FacturaInventario  $facturaInventario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFacturaInventarioRequest $request, FacturaInventario $facturaInventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FacturaInventario  $facturaInventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacturaInventario $facturaInventario)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CarritoInventario;
use App\Http\Requests\StoreCarritoInventarioRequest;
use App\Http\Requests\UpdateCarritoInventarioRequest;

class CarritoInventarioController extends Controller
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
     * @param  \App\Http\Requests\StoreCarritoInventarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarritoInventarioRequest $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function show(CarritoInventario $carritoInventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function edit(CarritoInventario $carritoInventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarritoInventarioRequest  $request
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarritoInventarioRequest $request, CarritoInventario $carritoInventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarritoInventario $carritoInventario)
    {
        //
    }
}

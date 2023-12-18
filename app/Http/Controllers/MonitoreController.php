<?php

namespace App\Http\Controllers;

use App\Models\Monitore;
use App\Http\Requests\StoreMonitoreRequest;
use App\Http\Requests\UpdateMonitoreRequest;

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
    public function store(StoreMonitoreRequest $request)
    {
        //
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

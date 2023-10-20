<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDashboardRequest;
use App\Http\Requests\UpdateDashboardRequest;
use App\Models\{
    Cliente,
    Dashboard,
    DataDev,
    Helpers,
    Producto,
    Proveedore,
};
use Illuminate\Support\Facades\Request;

class DashboardController extends Controller
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
            $menuSuperior = $this->data->menuSuperior;
            $pathname = Request::path();
            // Datos de las estadisticas
            $datosDash = [
                "totalProductos" => Producto::all()->count(),
                "totalClientes" => Cliente::all()->count(),
                "totalProveedores" => Proveedore::all()->count(),
            ];
            return view('admin.dashboard', compact('menuSuperior', 'pathname', 'datosDash'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar datos del dashboard, ");
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
     * @param  \App\Http\Requests\StoreDashboardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDashboardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDashboardRequest  $request
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDashboardRequest $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}

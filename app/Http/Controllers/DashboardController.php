<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDashboardRequest;
use App\Http\Requests\UpdateDashboardRequest;
use App\Models\{
    Cliente,
    Dashboard,
    DataDev,
    Factura,
    FacturaInventario,
    Helpers,
    Inventario,
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
                "totalProductos" => Inventario::count(),
                "totalClientes" => Cliente::count(),
                "totalProveedores" => Proveedore::count(),
                "totalFacturasPorPagar" => FacturaInventario::where([
                    "tipo" => 'ENTRADA',
                    "concepto" => 'CREDITO',
                ])->count(),

                "totalFacturasPorCobrar" => Factura::where([
                    "tipo" => 'SALIDA',
                    "concepto" => 'CREDITO',
                ])->count(),
               
            ];
            // return $datosDash;
            return view('admin.dashboard', compact('menuSuperior', 'pathname', 'datosDash'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al consultar datos del dashboard, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\DataDev;
use App\Models\Factura;
use App\Models\Helpers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class ClienteController extends Controller
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
            $clientes = Cliente::all();
            $pathname = Request::path();
      
            $menuSuperior = $this->data->menuSuperior;
            return view('admin.clientes.lista', compact('clientes', 'menuSuperior', 'pathname'));
        
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar los clientes, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
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
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClienteRequest $request)
    {
        try {

            $estatusCrear = Cliente::create($request->all());
    
            $mensaje = $estatusCrear ? "El cliente se creo correctamente." : "El cliente no se creo";
            $estatus = $estatusCrear ? 201 : 404;
            $pathname = Request::path();

            if($request->formulario == "modalCrearCliente"){
                return $estatusCrear ? redirect()->route( 'admin.pos.index', compact('mensaje', 'estatus') )
                : view('admin.pos.index', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', $request));
            }

            return $estatusCrear ? redirect()->route( 'admin.clientes.index', compact('mensaje', 'estatus') )
            : view('admin.clientes.lista', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', $request));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear la cliente, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        try {
            return response()->json([
                "message" => "Consulta exitosa",
                "data" => $cliente,
                "status" => Response::HTTP_OK
            ], Response::HTTP_OK);
        
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error al consultar producto por codigo de barra, " . $th->getMessage(),
                "data" => [],
                "status" => 500
            ], 500);
        }
    }

    public function getCliente($idCliente)
    {
        try {
            $resultado = Cliente::where('identificacion', $idCliente)->get();

            if(count($resultado)){
                return response()->json([
                    "message" => "Consulta exitosa",
                    "data" => $resultado[0],
                    "status" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    "message" => "Cliente no existe",
                    "data" => $resultado,
                    "status" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error al consultar producto por codigo de barra, " . $th->getMessage(),
                "data" => [],
                "status" => 500
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try {
            $estatusActualizar = $cliente->update($request->all());
    
            $mensaje = $estatusActualizar ? "El cliente se actualizó correctamente." : "Los cambios no se guardaron!";
            $estatus = $estatusActualizar ? 201 : 404;
            
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return $estatusActualizar ? redirect()->route( 'admin.clientes.index', compact('mensaje', 'estatus') )
            : view('admin.clientes.lista', compact('mensaje', 'estatus', 'menuSuperior', 'pathname',$request));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Actualizar la cliente, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        try {
            $clienteEnUso = Factura::where("identificacion", $cliente->identificacion)->get();
            if(count($clienteEnUso)){
                $mensaje = "El cliente tiene facturas registradas y no puede ser eliminado.";
                $estatus = 401;
                return redirect()->route( 'admin.clientes.index', compact('mensaje', 'estatus') );
            }

            $estatusEliminar = $cliente->delete();
    
            $mensaje = $estatusEliminar ? "El cliente se eliminó correctamente." : "No se pudo eliminar el cliente";
            $estatus = $estatusEliminar ? 201 : 404;
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return $estatusEliminar ? redirect()->route( 'admin.clientes.index', compact('mensaje', 'estatus') )
            : view('admin.clientes.lista', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', $cliente));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Eliminar la cliente, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
}

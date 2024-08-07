<?php

namespace App\Http\Controllers;

use App\Exports\ClienteExportar;
use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Imports\ClienteImport;
use App\Models\DataDev;
use App\Models\Factura;
use App\Models\Helpers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use \Excel;

class ClienteController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }


    /** API */
    public function getCliente(HttpRequest $request)
    {

        try {
            /** Validamos si el id esta definodo */
            if ($request == "undefined") {
                return response()->json([
                    "mensaje" => "Indentificacion no definida.",
                    "data" => $request,
                    "estatus" => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }

            /** Buscamos por cedula y nombre de barra */
            foreach ($request->campo as $key => $campo) {
                switch ($campo) {
                    case 'identificacion':
                        $resultado = Cliente::where("{$campo}", $request->filtro)->paginate(1);
                        if (count($resultado)) {
                            return response()->json([
                                "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR CEDULA",
                                "data" => $resultado,
                                "estatus" => Response::HTTP_OK
                            ], Response::HTTP_OK);
                        }
                        break;
                    case 'nombre':
                        $resultados = Cliente::where("{$campo}", 'like', "%{$request->filtro}%")->orderBy('nombre', 'asc')->paginate(15);
                        if (count($resultados)) {
                            return response()->json([
                                "mensaje" => "CONSULTA FILTRADA EXITOSAMENTE POR NOMBRE",
                                "data" => $resultados,
                                "estatus" => Response::HTTP_OK
                            ], Response::HTTP_OK);
                        } else {
                            return response()->json([
                                "mensaje" => "NO HAY REGISTROS.",
                                "data" => $resultados,
                                "estatus" => Response::HTTP_NOT_FOUND
                            ], Response::HTTP_NOT_FOUND);
                        }
                        break;

                    default:
                        return response()->json([
                            "mensaje" => "NO HAY REGISTROS.",
                            "data" => ["data" => []],
                            "estatus" => Response::HTTP_NOT_FOUND
                        ], Response::HTTP_NOT_FOUND);
                        break;
                }
            }
            return response()->json([
                "mensaje" => "NO HAY REGISTROS.",
                "data" => ["data" => []],
                "estatus" => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return response()->json([
                "mensaje" => "Error al consultar CLIENTE, " . $th->getMessage(),
                "data" => [],
                "estatus" => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function storeCliente(HttpRequest $request)
    {
        try {

            $clienteExiste = Cliente::where('identificacion', $request->identificacion)->get();

            if (count($clienteExiste)) {
                $mensaje = "El RIF o cédula del cliente ya esta registrado con otro cliente, por favor ingrese otro número de documento.";
                $estatus = Response::HTTP_UNAUTHORIZED;

                return response()->json([
                    "mensaje" => $mensaje,
                    "data" => $clienteExiste,
                    "estatus" => $estatus
                ], $estatus);
            } else {
                $estatusCrear = Cliente::create($request->all());
                $mensaje = $estatusCrear ? "El cliente se creo correctamente." : "El cliente no se creo";
                $estatus = $estatusCrear ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;
            }

            return response()->json([
                "mensaje" => "",
                "data" => $estatusCrear,
                "estatus" => $estatus
            ], $estatus);
        } catch (\Throwable $th) {
            return response()->json([
                "mensaje" => "Error al registrar el cliente, " . $th->getMessage(),
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function updateCliente(HttpRequest $request,  $idCliente)
    {
        try {
            $estatusEditar = "";
            $cliente = Cliente::where('id', $idCliente)->get();

            if (count($cliente)) {
                if ($cliente[0]->identificacion != $request->identificacion) {
                    $clienteExiste = Cliente::where('identificacion', $request->identificacion)->get();

                    if (count($clienteExiste)) {
                        $mensaje = "El RIF o cédula ingresado ya esta registrado con otro cliente, por favor ingrese un número de documento diferente";
                        $estatus = Response::HTTP_UNAUTHORIZED;
                    } else {
                        $estatusEditar = Cliente::where('id', $idCliente)->update($request->all());
                        $mensaje = $estatusEditar ? "El cliente se actualizó correctamente." : "El cliente no se actualizó";
                        $estatus = $estatusEditar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
                    }
                } else {
                    $estatusEditar = Cliente::where('id', $idCliente)->update($request->all());
                    $mensaje = $estatusEditar ? "El cliente se actualizó correctamente." : "El cliente no se actualizó";
                    $estatus = $estatusEditar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
                }
            } else {
                $mensaje = "El cliente se consiguio en los registros.";
                $estatus = Response::HTTP_NOT_FOUND;
            }
            $cliente = Cliente::where('id', $idCliente)->get();
            return response()->json([
                "mensaje" => $mensaje,
                "data" => $cliente,
                "estatus" => $estatus
            ], $estatus);
        } catch (\Throwable $th) {
            return response()->json([
                "mensaje" => $th->getMessage(),
                "data" =>  $cliente,
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /** CIERRE DE API */



    /** SECCION DE EXPORTACIÓN E IMPORTACIÓN */
    public function setImportarCliente(HttpRequest $request)
    {
        try {
            Excel::import(new ClienteImport, $request->file('file'));
            $mensaje = "Importación de datos realizada correctamente.";
            $estatus = Response::HTTP_OK;
            return back('admin.importar.create')->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "{$th->getMessage()}, error al importar clientes.");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return redirect()->route('admin.importar.create')->with(compact('mensaje', 'estatus'));
        }
    }

    public function exportExcel()
    {
        return Excel::download(new ClienteExportar, 'clientes.xlsx');
    }

    public function exportPdf()
    {
        return Excel::download(new ClienteExportar, 'clientes.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
    /** CIERRE SECCION DE EXPORTACIÓN E IMPORTACIÓN */


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HttpRequest $request)
    {
        try {
            if($request->filtro){

                $clientes = Cliente::where('id', $request->filtro)
                ->orWhere('identificacion', '=', $request->filtro)
                ->orWhere('nombre', 'LIKE', "%{$request->filtro}%")
                ->orderBy('id', 'desc')
                ->paginate(12);
            }else{

                $clientes = Cliente::orderBy('id', 'desc')->paginate(12);
            }



            $pathname = $request->path();
            $respuesta =  $this->data->respuesta;
            $menuSuperior = $this->data->menuSuperior;
            return view('admin.clientes.lista', compact('clientes', 'menuSuperior', 'pathname', 'request', 'respuesta'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar los clientes, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
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
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            $estatusCrear = 0;
            $respuesta = null;

            $clienteExiste = Cliente::where('identificacion', $request->identificacion)->get();
            if (count($clienteExiste)) {
                $mensaje = $this->data->respuesta['mensaje'] = "El cliente no se registro, porque el número de cédula ya existe.";
                $estatus  = $this->data->respuesta['estatus'] = Response::HTTP_UNAUTHORIZED;

                $respuesta = $this->data->respuesta;
            } else {
                $estatusCrear = Cliente::create($request->all());
                $this->data->respuesta['mensaje'] = $mensaje = $estatusCrear ? "El cliente se creo correctamente." : "El cliente no se creo";
                $this->data->respuesta['estatus'] = $estatus = $estatusCrear ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;
                $respuesta = $this->data->respuesta;
            }

            $clientes = Cliente::orderBy('id', 'desc')->paginate(10);
            return view('admin.clientes.lista', compact('respuesta', 'menuSuperior', 'pathname', 'request', 'clientes'));
            // return $estatus == Response::HTTP_CREATED ? redirect()->route( 'admin.clientes.index', compact('mensaje', 'estatus') )
            //     : view('admin.clientes.lista', compact('respuesta', 'menuSuperior', 'pathname', 'request', 'clientes'));
            // return redirect()->route( 'admin.clientes.index', compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = "Error al intentar registrar al cliente";
            $estatus = 404;
            return redirect()->route('admin.clientes.index', compact('mensaje', 'estatus'));
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
            return $estatusActualizar ? redirect()->route('admin.clientes.index', compact('mensaje', 'estatus'))
                : view('admin.clientes.lista', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', $request));
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

            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            $clientes  = [];
            $clienteEnUso = Factura::where("identificacion", $cliente->identificacion)->get();
            if (count($clienteEnUso)) {
                $this->data->respuesta['mensaje'] = $mensaje = "El cliente tiene facturas registradas y no puede ser eliminado.";
                $this->data->respuesta['estatus'] = $estatus = Response::HTTP_UNAUTHORIZED;
                $clientes = Cliente::orderBy('id', 'desc')->paginate(10);
            } else {
                $estatusEliminar = $cliente->delete();
                $clientes = Cliente::orderBy('id', 'desc')->paginate(10);
                $this->data->respuesta['mensaje'] = $mensaje = $estatusEliminar ? "El cliente se eliminó correctamente." : "No se pudo eliminar el cliente";
                $this->data->respuesta['estatus'] = $estatus = $estatusEliminar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
            }

            $respuesta = $this->data->respuesta;
            return view('admin.clientes.lista', compact('menuSuperior', 'pathname', 'clientes', 'respuesta'));
            // return $estatusEliminar ? redirect()->route( 'admin.clientes.index', compact('mensaje', 'estatus') )
            // : view('admin.clientes.lista', compact('mensaje', 'estatus', 'menuSuperior', 'pathname', $cliente));
        } catch (\Throwable $th) {
            // $this->data->respuesta['mensaje'] = $mensaje = "El cliente tiene facturas registradas y no puede ser eliminado.";
            // $this->data->respuesta['estatus'] = $estatus = Response::HTTP_UNAUTHORIZED;
            // $clientes = Cliente::orderBy('id', 'desc')->paginate(10);
            // $respuesta = $this->data->respuesta;
            // $menuSuperior = $this->data->menuSuperior;
            // return view('admin.clientes.lista', compact('menuSuperior', 'clientes', 'respuesta'));
            // $mensajeError = Helpers::getMensajeError($th, "Error Al Eliminar la cliente, ");
            // return view('errors.404', compact('mensajeError'));
        }
    }
}

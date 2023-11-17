<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Http\Requests\StoreCarritoRequest;
use App\Http\Requests\UpdateCarritoRequest;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Inventario;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class CarritoController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }

    public function eliminarCarritoCompleto($codigo)
    {
        try {
            $carritoExiste = Carrito::where('codigo', $codigo)->get();
            if (count($carritoExiste)) {
                $estatusEliminar = Carrito::where('codigo', $codigo)->delete();
                $mensaje = $estatusEliminar ? "La Factura se eliminó correctamente." : "No se pudo eliminar la factura";
                $estatus = $estatusEliminar ? 201 : 404;
            } else {
                $mensaje = "La factura no se ha registrado, no se encontro factura que eliminar.";
                $estatus = 404;
            }

            return redirect()->route('admin.pos.index', compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Elimianr factura, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

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
     * API CARRITO
     */
    /** Listar carrito */
    public function getCarrito($codigoFactura)
    {
        try {
            $carrito = Carrito::where('codigo', $codigoFactura)->get();
            if (count($carrito)) {
                return response()->json([
                    "mensaje" => "Consulta exitosa del carrito",
                    "data" => $carrito,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "mensaje" => "No hay carrito facturado con el codigo de factura ingresado",
                    "data" => $carrito,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar carrito de compra");
            return response()->json([
                "mensaje" => $mensaje,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** Registrar producto en el carrito */
    public function facturarCarrito(HttpRequest $request)
    {
        try {
       
            /** Validadmos que la cantida no sobre pase la del inventario */
            $productoEnInventario = Inventario::where('codigo', $request->codigo_producto)->get();
            if (count($productoEnInventario)) {
                if ($request->cantidad > $productoEnInventario[0]->cantidad) {
                    return response()->json([
                        "mensaje" => "La Existencia es insuficiente para facturar el producto {$request->descripcion} | Disponibles: {$request->cantidad} .",
                        "data" => $productoEnInventario,
                        "estatus" => Response::HTTP_UNAUTHORIZED
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }

            /** Registramos el producto en el carrito DB */
            $resultado = Carrito::create($request->all());
            return response()->json([
                "mensaje" => "El carrito se facturó correctamente",
                "data" => $resultado,
                "estatus" => Response::HTTP_OK,
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al intentar registrar factura, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  $request->request,
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** Editar el producto del carrito */
    /** Eliminar producto del carritos */
    /** Eliminar todo el carrito */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function show(Carrito $carrito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function edit(Carrito $carrito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarritoRequest  $request
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarritoRequest $request, Carrito $carrito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carrito $carrito)
    {
        try {
            $identificacion = $carrito->identificacion;
            $menuSuperior = $this->data->menuSuperior;
            $carrito->delete();

            $carritos = Carrito::where('codigo', $carrito->codigo)->get();
            $codigo = $carrito->codigo;
            return redirect()->route('admin.pos.index', compact('identificacion'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al eliminar la producto del carrito, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
}

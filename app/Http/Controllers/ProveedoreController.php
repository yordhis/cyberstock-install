<?php

namespace App\Http\Controllers;

use App\Models\{
    DataDev,
    Helpers,
    Proveedore
};
use App\Http\Requests\StoreProveedoreRequest;
use App\Http\Requests\UpdateProveedoreRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class ProveedoreController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }

    /** API PROVEEDOR */
    public function getProveedor($idProveedor)
    {
        
        try {
            /** Validamos si el id esta definodo */
            if($idProveedor == "undefined"){
                return response()->json([
                    "mensaje" => "Indentificacion o rif no definida.",
                    "data" => $idProveedor,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }

            /** Realizamos la consulta de la data del Proveedor */
            $resultado = Proveedore::where('codigo', $idProveedor)->get();

            /** Validamos si hay un resultado */
            if(count($resultado)){
                return response()->json([
                    "mensaje" => "Consulta exitosa",
                    "data" => $resultado,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    "mensaje" => "Proveedor NO registrado!",
                    "data" => $resultado,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        
        } catch (\Throwable $th) {
            return response()->json([
                "mensaje" => "Error al consultar Proveedor, " . $th->getMessage(),
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeProveedor(HttpRequest $request){
        try {
          
            $proveedorExiste = Proveedore::where('codigo', $request->identificacion)->get();
        
            if(count($proveedorExiste)){
                $mensaje = "El Proveedor Ya existe.";
                $estatus = Response::HTTP_UNAUTHORIZED;

                return response()->json([
                    "mensaje" => $mensaje,
                    "data" => $proveedorExiste,
                    "estatus" => $estatus
                ], $estatus);

            }else{
                /** Procedemos a crear */
                $estatusCrear = Proveedore::create($request->all());
                $mensaje = $estatusCrear ? "El Proveedor se creo correctamente." : "El Proveedor no se creo";
                $estatus = $estatusCrear ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;
            }

            return response()->json([
                "mensaje" => $mensaje,
                "data" => $estatusCrear,
                "estatus" => $estatus
            ], $estatus);


        } catch (\Throwable $th) {
            return response()->json([
                "mensaje" => "Error al registrar el Proveedor, " . $th->getMessage(),
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function updateProveedor( HttpRequest $request,  $idProveedor ){
        try {
          
            $proveedor = Proveedore::where('id', $idProveedor)->get();
            /** Validamos si edito la cedula o rif */
            if(count($proveedor)){
                if( $proveedor[0]->identificacion != $request->identificacion ){
                    $proveedorExiste = Proveedore::where('identificacion', $request->identificacion)->get();
                    
                    if(count($proveedorExiste)){
                        $mensaje = "El Proveedor Ya existe.";
                        $estatus = Response::HTTP_UNAUTHORIZED;
                    }else{
                        $estatusEditar = Proveedore::where( 'id', $idProveedor )->update($request->all());
                        $mensaje = $estatusEditar ? "El Proveedor se actualizó correctamente." : "El Proveedor no se actualizó";
                        $estatus = $estatusEditar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
                    }
                }else{
                    $estatusEditar = Proveedore::where( 'id', $idProveedor )->update($request->all());
                    $mensaje = $estatusEditar ? "El Proveedor se actualizó correctamente." : "El Proveedor no se actualizó";
                    $estatus = $estatusEditar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
                }
            }else{
                $mensaje = "El Proveedor se consiguio en los registros.";
                $estatus = Response::HTTP_NOT_FOUND;
            }
            $proveedor = $estatusEditar ? $proveedor = Proveedore::where('id', $idProveedor)->get() : [];
            return response()->json([
                "mensaje" => $mensaje,
                "data" => $proveedor,
                "estatus" => $estatus
            ], $estatus);


        } catch (\Throwable $th) {
            return response()->json([
                "mensaje" => "Error al editar el Proveedor, " . $th->getMessage(),
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /** CIERRE API PROVEEDOR */


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedores = Proveedore::all();
        $pathname  = Request::path();
        $menuSuperior = $this->data->menuSuperior;
        return view('admin.proveedores.lista', compact('menuSuperior', 'proveedores', 'pathname'));
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
     * @param  \App\Http\Requests\StoreProveedoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProveedoreRequest $request)
    {
        try {
          
            //$resulProveedor = Proveedore::where('codigo', $request->codigo)->get();
             
            // Validamos si ya existe el proveedor
            //if(count($resulProveedor)){
                //$mensaje = "Este proveedor ya existe";
                //$estatus = 404;
                //return redirect()->route("admin.proveedores.index", compact('mensaje', 'estatus'));
            //}else{
                
                if($request->file){
                    $url = Helpers::setFile($request);
                    $request['imagen'] = $url;
                }else {
                    $request['imagen'] = FOTO_PORDEFECTO;
                }
    
                $estatusCrear = Proveedore::create($request->all());
    
                $mensaje = $estatusCrear ? "Proveedor registrado correctamente" : "No se registro el proveedor";
                $estatus = $estatusCrear ? 201 : 404;
    
         
                if($request->formulario == "inventarios/crearEntrada"){
                    return redirect()->route('admin.inventarios.crearEntrada', compact('mensaje', 'estatus'));
                }else{
                    return redirect()->route("admin.proveedores.index", compact('mensaje', 'estatus'));
                }
            //}
            // Seteamos la imagen

        } catch (\Throwable $th) {
            $mensaje = "No se registro el proveedor";
            $estatus = 404;
            return redirect()->route("admin.proveedores.index", compact('mensaje', 'estatus'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedore $proveedore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedore $proveedore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProveedoreRequest  $request
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProveedoreRequest $request, Proveedore $proveedore)
    {
        try {
            $estatusActualizar = $proveedore->update($request->all());
            $mensaje = $estatusActualizar ? "Proveedor Se actualizó correctamente" : "No se pudieron guardar los cambios del proveedor";
            $estatus = $estatusActualizar ? 200 : 404;
       
            return redirect()->route('admin.proveedores.index', compact('mensaje', 'estatus'));
            
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear el producto, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedore  $proveedore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedore $proveedore)
    {
        $estatusEliminar = $proveedore->delete();

        $mensaje = $estatusEliminar ? "Proveedor Se Eliminó correctamente" : "No se pudo eliminar el proveedor";
        $estatus = $estatusEliminar ? 200 : 404;

        return redirect()->route('admin.proveedores.index', compact('mensaje', 'estatus')); 
    }
}

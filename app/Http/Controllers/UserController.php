<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\{
    User,
    DataDev,
    Helpers,
    Permiso,
    Role
};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    public $data;
    /**
     * Constructor
     */
     public function __construct(){
        $this->data = new DataDev;
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $usuarios = Helpers::getUsuarios();
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return view('admin.usuarios.lista', compact('menuSuperior', 'pathname', 'usuarios'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al Consultar datos de usuarios en el metodo index,");
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
        try {
            $roles = Role::where('nombre', '!=', 'root')->get();
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            return view('admin.usuarios.crear', compact('roles', 'pathname', 'menuSuperior'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al Consultar datos de usuarios en el metodo create,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUsuarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            
            $roles = Role::where('nombre', '!=', 'root')->get();
            $estatusCreate = 0;

            $datoExiste = User::where('email', $request->email)->get();
            
            if(!count($datoExiste)){
                // Seteamos la foto
                if(isset($request->file)){
                    $request['foto'] = Helpers::setFile($request);
                }
                // Encriptamos la contraseña
                $request['password'] = Hash::make($request->password);
                // Creamos el usuario
                $estatusCreate = User::create($request->all());
            }
            $mensaje = $this->data->respuesta['mensaje'] = $estatusCreate ? "El Usuario se Registró correctamente."
                                      : "El nombre de Usuario ¡Ya existe!, Cambie el nombre.";
            $estatus = $this->data->respuesta['estatus'] = $estatusCreate ? 200 
                                      : 301;
            $respuesta = $this->data->respuesta;
            
            return $estatusCreate ? redirect()->route('admin.users.index', compact('mensaje', 'estatus'))
                                  : view('admin.usuarios.crear', compact('request', 'menuSuperior', 'pathname', 'respuesta', 'roles') );
    
            
            
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al Registrar los datos de usuarios en el metodo store,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, $request)
    {
       
        try {
            $pathname = Request::path();
            $menuSuperior = $this->data->menuSuperior;
            $usuarioEdit = User::where('id',$request)->get()[0];
          
            $roles = Role::where('id','>', 1)->get();
            return view('admin.usuarios.editar', compact('pathname', 'menuSuperior', 'usuarioEdit', 'roles'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de consula,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUsuarioRequest  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
     
        try {
    
            $user = count(User::where('id',$id)->get()) ? User::where('id',$id)->get()[0]: User::where('id',$id)->get();
             // Validamos si se envio una foto
            if (isset($request->file)) {
                // Eliminamos la imagen anterior
                $fotoActual = explode('/', $user->foto);
                if($fotoActual[count($fotoActual)-1] != 'default.jpg'){
                    Helpers::removeFile($user->foto);
                }
                // Insertamos la nueva imagen o archivo
                $request['foto'] = Helpers::setFile($request);
                
            } else {
                $request['foto'] = $user->foto;
            }
             // Encriptamos la contraseña
             if (isset($request['password'])) {
                 $request['password'] = Hash::make($request['password']);
             }else{
                $request['password'] = $user['password'];
             }

            if($user->update($request->all())){
                $mensaje = "El Usuario se Actualizó correctamente.";
                $estatus = 200;
                return redirect()->route('admin.users.index', compact('mensaje', 'estatus'));
            }
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de al intentar Actualizar un usuario,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, $request)
    {
        try {
       
            User::destroy("id", $request);
            $mensaje = "El Usuario se Eliminó correctamente.";
            $estatus = 200;
            return redirect()->route( 'admin.users.index', compact('mensaje', 'estatus') );
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de al intentar Eliminar un usuario,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }
}

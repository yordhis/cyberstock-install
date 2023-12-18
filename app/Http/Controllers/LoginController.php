<?php

namespace App\Http\Controllers;

use App\Models\Helpers;
use App\Models\Monitore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class LoginController extends Controller
{
    /** API DE VERIFICAR AUTORIZACION */
    public function verificarClave(Request $request){
        $emails = User::select('email')->where('rol', '2')->get();
       

        foreach ($emails as $key => $email) {
            $request['email'] = $email->email;
            // Autenticamos al usuario
            $credenciales = $request->only('email', 'password');

            if (Auth::attempt($credenciales)) {
                return response()->json([
                    "mensaje" => "Acción autorizada",
                    "data" => true,
                    "estatus" => Response::HTTP_OK
                ],Response::HTTP_OK);
            }else{
                $mensaje = "Acción NO autorizada";
                $data = false;
                $estatus = Response::HTTP_UNAUTHORIZED;
            }
        }

        return response()->json([
            "mensaje" => $mensaje,
            "data" => $data,
            "estatus" => $estatus
        ],$estatus);
        
         
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** Validamos */
        $request->validate([
            "email" => 'required',
            "password" => 'required'
        ]);

        // Autenticamos al usuario
        $credenciales = $request->only('email', 'password');

        // $recuerdame = isset($request->recuerdame) ? true : false;
        $recuerdame = $request->filled('rememberMe');

        if (Auth::attempt($credenciales, $recuerdame)) {
            $request->session()->regenerate();
            
             /** registramos movimiento al usuario */
             Helpers::registrarMovimientoDeUsuario($request);

            return redirect()->intended('panel');
        }

        return back()->withErrors([
            'mensajeError' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request, Redirector $redirect)
    {
        /** registramos movimiento al usuario */
        Helpers::registrarMovimientoDeUsuario($request);
        // Eliminamos la session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return $redirect->to('/login');
    }
}

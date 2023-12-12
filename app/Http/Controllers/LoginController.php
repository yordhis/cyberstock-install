<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class LoginController extends Controller
{

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
            // return "esta logeado";
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
        
        // Eliminamos la session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return $redirect->to('/login');
    }
}

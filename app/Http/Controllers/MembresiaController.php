<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Http\Requests\StoreMembresiaRequest;
use App\Http\Requests\UpdateMembresiaRequest;
use App\Models\DataDev;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class MembresiaController extends Controller
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
        $menuSuperior = $this->data->menuSuperior;
        $respuesta = $this->data->respuesta;
        
        $token = count(Membresia::all()) ? Membresia::all()[0] : [];
     
        if(count(Membresia::all())){
            $membresia = explode(",", Crypt::decrypt($token->token, false));
            array_push($membresia, $token->id);
        }else{
            $membresia = [];
        }
 
        
        return view('admin.membresias.lista', compact('membresia', 'menuSuperior', 'respuesta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuSuperior = $this->data->menuSuperior;
        $respuesta = $this->data->respuesta;
      
        return view('admin.membresias.crear', compact('menuSuperior', 'respuesta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMembresiaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMembresiaRequest $request)
    {
        $payload  = "{$request->paquete},{$request->empresa},{$request->rif},{$request->fecha_inicio},{$request->fecha_fin}";
    
        $token = Crypt::encryptString($payload);

        $membresia = Membresia::create(["token" => $token]);

        if($membresia){
            return redirect()->route('admin.membresias.index')->with([
                "mensaje" => "La membresia se registro correctamente",
                "estatus" => Response::HTTP_OK
            ]);

        }else{
            return redirect()->route('admin.membresias.index')->with([
                "mensaje" => "La membresia no se registro, vuelve a intentar",
                "estatus" => Response::HTTP_NOT_FOUND
            ]);
        }
  
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Membresia  $membresia
     * @return \Illuminate\Http\Response
     */
    public function edit(Membresia $membresia)
    {
        $menuSuperior = $this->data->menuSuperior;
        $respuesta = $this->data->respuesta;

        $membresiaDecrypt = explode(",", Crypt::decrypt($membresia->token, false));
        array_push($membresiaDecrypt, $membresia->id);
      
        return view('admin.membresias.editar', compact('menuSuperior', 'respuesta', 'membresiaDecrypt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMembresiaRequest  $request
     * @param  \App\Models\Membresia  $membresia
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMembresiaRequest $request, Membresia $membresia)
    {

        $payload  = "{$request->paquete},{$request->empresa},{$request->rif},{$request->fecha_inicio},{$request->fecha_fin}";
    
        $token = Crypt::encryptString($payload);

        if( $membresia->update(["token" => $token]) ){
            return redirect()->route('admin.membresias.index')->with([
                "mensaje" => "La membresia se actualizó correctamente",
                "estatus" => Response::HTTP_OK
            ]);

        }else{
            return redirect()->route('admin.membresias.index')->with([
                "mensaje" => "La membresia no se actualizó, vuelve a intentar",
                "estatus" => Response::HTTP_NOT_FOUND
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Membresia  $membresia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Membresia $membresia)
    {
        $membresia->delete();
        return redirect()->route('admin.membresias.index')->with([
            "mensaje" => "La membresia se eliminó correctamente",
            "estatus" => Response::HTTP_OK
        ]);
    }
}

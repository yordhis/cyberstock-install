<?php

namespace App\Http\Controllers;

use App\Models\{
    CarritoInventario,
    DataDev,
    FacturaInventario,
    Proveedore
};
use Illuminate\Http\Request;

class PagarController extends Controller
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
    
            $pagar = FacturaInventario::where([
                "tipo" => 'ENTRADA',
                "concepto" => 'CREDITO',
            ])->paginate(10);
    
            foreach ($pagar as $key => $value) {
               $value['proveedor']= Proveedore::where('codigo', $value->identificacion)->get();
               $value['carrito'] = CarritoInventario::where('codigo', $value->codigo)->get();
                $contador = 0;
               foreach($value['carrito'] as $cantidade){
                $value['totalArticulos'] += $cantidade->cantidad; 
               }
            }
            // return $pagar;
            return view('admin.pagar.lista', compact('pagar', 'menuSuperior'));
        
    
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

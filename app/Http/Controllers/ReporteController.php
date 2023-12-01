<?php

namespace App\Http\Controllers;

use App\Models\{
    DataDev
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ReporteController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }

    public function index(){
        $menuSuperior = $this->data->menuSuperior;
        $pathname = FacadesRequest::path();
        return view('admin.reportes.index', compact('menuSuperior', 'pathname'));
    }

}

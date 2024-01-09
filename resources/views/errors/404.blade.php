@extends('layouts.index')

@section('title', 'Not Found')

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <h1>Información del error:</h1>
            <p class="fs-5 text-danger">
                {{$errorInfo ?? 'Error 404 (No se halló el elemento) click en el botón volver para regresar al sistema.'}}
            </p>

            <a href="/panel"  class="btn btn-danger">VOLVER</a>
        </div>
    </div>
    
@endsection
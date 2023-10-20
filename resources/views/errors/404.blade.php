@extends('layouts.index')

@section('title', 'Not Found')

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <h1>Información del error:</h1>
            <p class="fs-5 text-danger">
                {{$errorInfo ?? 'No hay mensaje de error por favor llame a soporte. '}}
            </p>

            <a href="/panel" target="_self" class="btn btn-primary">Volver al panel</a>
        </div>
    </div>
    
@endsection
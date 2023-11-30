@extends('layouts.index')

@section('title', 'Not Found')

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <h1>Información del error:</h1>
            <p class="fs-5 text-danger">
                {{$errorInfo ?? 'Error 404'}}
            </p>

            <a href="/panel" target="_self" class="btn btn-danger">VOLVER</a>
        </div>
    </div>
    
@endsection
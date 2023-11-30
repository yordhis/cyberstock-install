@extends('layouts.index')

@section('title', 'Not Found')

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <h1>Información de error del servidor:</h1>
            <p class="fs-5 text-danger">
                {{$errorInfo ?? "Error 500"}}
            </p>

            <a href="/login" target="_self" class="btn btn-danger">VOLVER</a>
        </div>
    </div>
    
@endsection
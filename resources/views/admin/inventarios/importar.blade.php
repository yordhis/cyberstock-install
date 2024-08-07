@extends('layouts.app')

@section('title', 'Importar inventario')

@section('content')


    @include('partials.alertSession')

    <div id="alert"></div>

    <section class="section">
        <div class="row">

    

            <div class="col-sm-12">
                <h2> Importar inventario </h2>
            </div>

            <form action="{{ route('admin.importar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="input-group mb-3">
                    <label class="input-group-text bg-primary text-white" for="inputGroupFile01">Cargar Excel</label>
                    <input type="file" accept=".xlsx" name="file" class="form-control" id="inputGroupFile01" required>
                </div>

                <button type="submit" class="btn btn-success">Ejecutar carga de datos</button>
            </form>

            <hr>

            <div class="col-sm-12">
                <h2> Importar Clientes </h2>
            </div>

            <form action="{{ route('admin.importar.cliente') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="input-group mb-3">
                    <label class="input-group-text bg-primary text-white" for="inputGroupFile01">Cargar Excel</label>
                    <input type="file" accept=".xlsx" name="file" class="form-control" id="inputGroupFile01" required>
                </div>

                <button type="submit" class="btn btn-success">Ejecutar carga de datos</button>
            </form>





        </div>
    </section>

@endsection

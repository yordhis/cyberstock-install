@extends('layouts.app')

@section('title', 'Inventario')

@section('content')

    @isset($respuesta['activo'])
        @include('partials.alert')
    @endisset

    <div id="alert"></div>

    <section class="section">
        <div class="row">



            <div class="col-sm-12">
                <h2> Inventario </h2>
            </div>
    
            <div class="col-sm-6 col-xs-12">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="filtro-descripcion" placeholder="Buscar producto por Código o Descripcion" aria-label="Buscar producto" aria-describedby="basic-addon1">
                    <span class="text-danger invalido"></span>
                </div>
            </div>
            {{-- <div class="col-sm-4 col-xs-12">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-qr-code"></i></span>
                    <input type="text" class="form-control" id="filtro-codigo" placeholder="Ingrese código" aria-label="Buscar producto" aria-describedby="basic-addon1">
                    <span class="text-danger invalido"></span>
                </div>
            </div> --}}

            <div class="col-sm-4 col-xs-12">
                <div class="input-group mb-3">
                    <input type="submit" class="btn btn-success form-control" id="filtro-limpiar" value="Limpiar Filtro" >
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body table-responsive">

                        <!-- Table with stripped rows -->

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Código</th>
                                    <th scope="col" >Descripción</th>
                                    {{-- <th scope="col">fecha de entrada</th> --}}
                                    <th scope="col">Stock</th>
                                    <th scope="col">Costo</th>
                                    <th scope="col">PVP</th>
                                    <th scope="col">PVP USD</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Categoria</th>
                                 
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista">
                               

                            </tbody>
                        </table>

                        <!-- End Table with stripped rows -->
                        <nav class="paginacion" aria-label="Page navigation example"></nav>
                    </div>
                </div>

            </div>



        </div>
    </section>

  



@endsection

@extends('layouts.app')

@section('title', 'Inventario')

@section('content')

    @isset($respuesta['activo'])
        @include('partials.alert')
    @endisset


    <section class="section">
        <div class="row">

           

            <div class="card">
                <div class="card-tlite">
                    <h1>Configuración de porcentaje</h1>
                    <hr>
                </div>
                <div class="card-body">
                    <form action="getInventariosFiltro" id="formularioFiltro" method="post" target="_self" >
                        <div class="row">
                         
                            <div class="col-sm-4 col-xs-12">
                                <label for="porcentaje">Porcentaje</label>
                                <input type="number" class="form-control" id="porcentaje" name="porcentaje" placeholder="Ingrese porcentaje" aria-label="Buscar producto" aria-describedby="basic-addon1" required>
                                <span class="text-danger "></span>
                            </div>

                            <div class="col-sm-4 col-xs-12">
                                <label for="codigo_descripcion">Código o descripción</label>
                                <input type="text" class="form-control" id="filtro" name="filtro" placeholder="Configurar por código o descripción" aria-label="Buscar producto" aria-describedby="basic-addon1">
                                <span class="text-danger"></span>
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                                <label for="categoria">Categoria</label>
                                <select class="form-select" id="categorias" name="id_categoria">
                                    <option selected>CATEGORIAS</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"> {{ $categoria->nombre }} </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"></span>
                            </div>
    
                            <div class="col-sm-4 col-xs-12">
                                <label for="marca">Marca</label>
                                <select class="form-select" id="marcas" name="id_marca">
                                    <option selected>MARCAS</option>
                                    @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}"> {{ $marca->nombre }} </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"></span>
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                                <button class="btn btn-outline-success mt-4 w-100" type="submit">
                                    Previsualizar Configuración
                                </button>
                            </div>

                            <div class="col-sm-4 col-xs-12">
                                <button class="btn btn-outline-danger w-100 mt-4" type="button" id="limpiarFiltro">
                                    <i class="bi bi-trash3"></i> Limpiar config.
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-2">
            </div>
    

            <div class="col-lg-12">
                <div id="alert"></div>

                <div class="card">
                    <div class="card-body table-responsive">

                        <!-- Table with stripped rows -->

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="bg-primary text-white border">#</th>
                                    <th scope="col" class="bg-primary text-white border">Código</th>
                                    <th scope="col" class="bg-primary text-white border" >Descripción</th>
                                    <th scope="col" class="bg-primary text-white border">Marca</th>
                                    <th scope="col" class="bg-primary text-white border">Categoria</th>
                                    {{-- <th scope="col">fecha de entrada</th> --}}
                                    <th scope="col"  class="bg-primary text-white border">Costo antes</th>
                                    <th scope="col" class="bg-primary text-white border">PVP 1 antes</th>
                                    <th scope="col" class="bg-primary text-white border">PVP 2 antes</th>
                                    <th scope="col" class="bg-primary text-white border">PVP 3 antes</th>
                                    
                                    <th scope="col" class="bg-warning text-secondary border">Costo despues</th>
                                    <th scope="col" class="bg-warning text-secondary border">PVP 1 despues</th>
                                    <th scope="col" class="bg-warning text-secondary border">PVP 2 despues</th>
                                    <th scope="col" class="bg-warning text-secondary border">PVP 3 despues</th>
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
            
            <div class="col-sm-12">
                <form action="porcentajes" id="setPorcentajes">
                    <button class="btn btn-primary w-100">
                        Guardar configuración de porcentajes
                    </button>
                </form>
            </div>



        </div>
    </section>

    


    <script src=" {{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/partials/customModal.js') }}" defer></script>
    <script src="{{ asset('js/porcentajes/index.js') }}" defer></script>
    <script src="{{ asset('js/porcentajes/porcentajeController.js') }}" defer></script>


@endsection

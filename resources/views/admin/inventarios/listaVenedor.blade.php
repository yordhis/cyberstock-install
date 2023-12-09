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
            <div class="col-sm-10 mb-1">
                <form action="getProductosFiltro" id="formularioFiltro" method="post" target="_self">
                    <div class="input-group">
                     
                        <input type="text" class="form-control" id="filtro" name="filtro" placeholder="Buscar producto por Código o Descripcion" aria-label="Buscar producto" aria-describedby="basic-addon1">
                        <span class="text-danger invalido"></span>
    
                        <select class="form-select" id="categorias" name="id_categoria">
                            <option selected>CATEGORIAS</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"> {{ $categoria->nombre }} </option>
                            @endforeach
                     
                        </select>
    
                        <select class="form-select" id="marcas" name="id_marca">
                            <option selected>MARCAS</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}"> {{ $marca->nombre }} </option>
                            @endforeach
                        </select>

                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-sm-2">
                <button class="btn btn-outline-danger w-100" type="bottom" id="limpiarFiltro">
                    <i class="bi bi-trash3"></i> Limpiar filtro
                </button>
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
                                    <th scope="col">Stock</th>
                                    <th scope="col">Costo</th>
                                    <th scope="col">PVP</th>
                                    <th scope="col">PVP USD</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Categoria</th>
                        
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

    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('/assets/js/main.js') }}" defer></script>

    <script src=" {{ asset('/js/main.js') }}" defer></script>
    <script src="{{ asset('/js/inventarios/vendedor.js') }}" defer></script>
    <script src="{{ asset('/js/inventarios/inventarioController.js') }}" defer></script>

@endsection

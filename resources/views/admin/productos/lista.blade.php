@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-8">
                <h2>Lista de Productos</h2>
            </div>
            <div class="col-sm-4 text-end">
                @include('admin.productos.partials.modalCrear')
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
    

            <div class="col-lg-12 mt-1">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                        {{-- <table class="table datatable "> --}}
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Código de barra</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Categoria</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="lista">
                                </tbody>
                            </table>
                            <!-- PAGINACION JS -->
                            <nav class="paginacion" aria-label="Page navigation example"></nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src=" {{ asset('js/main.js') }}" defer></script>

    <script src="{{ asset('js/productos/productoController.js') }}" defer></script>
    <script src="{{ asset('js/productos/categorias/CategoriaController.js') }}" defer></script>
    <script src="{{ asset('js/productos/marcas/MarcaController.js') }}" defer></script>
    <script src="{{ asset('js/productos/index.js') }}" defer></script>
    
 

@endsection

@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif


    {{-- respuesta de validadciones --}}
    <div class="col-12">
        @if ($errors->any())
            <div class="alert alert-danger text-start">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <section class="section">
        <div class="row">

            <div class="col-12">
                <h2> Lista de productos </h2>
            </div>
            <div class="col-sm-6 col-xs-12">
                
                @include('admin.productos.partials.modalCrear')
            </div>
            <div class="col-sm-6 col-xs-12">
                <form action="{{ route('admin.productos.index') }}" method="post">
                    @csrf
                    @method('get')
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="filtro"
                            placeholder="Filtrar (Por codigo de barra y Por descripción )" aria-label="Filtrar"
                            aria-describedby="button-addon2" required>
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                
            </div>

            <div class="col-lg-12 mt-4 ">

                <div class="table-responsive">
                    <!-- LISTA DE PRODUCTOS -->
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th scope="col">N°</th>
                                <th scope="col">Código de barra</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Acciones</th>
                                {{-- <th scope="col">@include('admin.clientes.partials.modalimprir')</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                                <tr>
                                    <td scope="row">{{ $producto->id }}</td>
                                    <td scope="row">{{ $producto->codigo }}</td>
                                    <td>{{ $producto->descripcion }}</td>
                                    <td>{{ $producto->categoria ?? 'N/A'}}</td>
                                    <td>{{ $producto->marca ?? 'N/A' }}</td>

                                    <td>
                                        
                                        @include('admin.productos.partials.modalEditar')
                                        @include('admin.productos.partials.modaldialog')
                                        @include('admin.productos.partials.modalEliminar')

                                    </td>
                                    {{-- <td></td> --}}
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>

                                <td colspan="7" class="text-center table-secondary">
                                    Total de productos: {{ $productos->total() }} |
                                    <a href="{{ route('admin.productos.index') }}" class="text-primary">
                                        Ver todo
                                    </a>
                                    <br>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- End LISTA DE CLIENTES -->
                    <!-- PAGINACIÓN DE CLIENTES -->
                    <div class="col-xs-12 col-sm-6 ">
                        {{ $productos->appends(['filtro' => $request->filtro])->links() }}
                    </div>
                    <!-- CIERRE PAGINACIÓN DE CLIENTES -->
                </div>

            </div>
        </div>
    </section>

    <script src="{{ asset('js/productos/generadorDeBarcode.js') }}" defer></script>


@endsection

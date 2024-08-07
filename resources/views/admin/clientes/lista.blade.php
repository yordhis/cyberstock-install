@extends('layouts.app')

@section('title', 'Lista de Clientes')

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
                <h2> Lista de clientes </h2>
            </div>
            <div class="col-sm-6 col-xs-12">
                @include('admin.clientes.partials.modalCrear')
            </div>
            <div class="col-sm-6 col-xs-12">
                <form action="{{ route('admin.clientes.index') }}" method="post">
                    @csrf
                    @method('get')
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="filtro"
                            placeholder="Filtrar (Por codigo de cliente, Por cédula o Por nombre)" aria-label="Filtrar"
                            aria-describedby="button-addon2" required>
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-12 mt-4 ">

                <div class="table-responsive">
                    <!-- LISTA DE CLIENTES -->
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th scope="col">Código</th>
                                <th scope="col">Nombre y Apellido</th>
                                <th scope="col">RIF O documento</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td scope="row">{{ $cliente->id }}</td>
                                    <td>{{ $cliente->nombre }}</td>
                                    <td>{{ $cliente->tipo . "-" . number_format($cliente->identificacion, 0, ',', '.') ?? '' }}</td>
                                    <td>{{ $cliente->telefono ?? 'No registrado.' }}</td>

                                    <td>

                                        @include('admin.clientes.partials.modalEditar')
                                        @include('admin.clientes.partials.modaldialog')
                                        @include('admin.clientes.partials.modal')

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>

                                <td colspan="7" class="text-center table-secondary">
                                    Total de clientes: {{ $clientes->total() }} |
                                    <a href="{{ route('admin.clientes.index') }}" class="text-primary">
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
                        {{ $clientes->appends(['filtro' => $request->filtro])->links() }}
                    </div>
                    <!-- CIERRE PAGINACIÓN DE CLIENTES -->
                </div>

            </div>
        </div>
    </section>




@endsection

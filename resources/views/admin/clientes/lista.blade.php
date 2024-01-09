@extends('layouts.app')

@section('title', 'Lista de Clientes')

@section('content')
     @isset($respuesta)
        @include('partials.alert')
    @endisset
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-8">
                <h2>Lista de Clientes</h2>
            </div>
            <div class="col-sm-4 text-end">
                @if($errors->any())
                    <div class="text-danger">El cliente no se registro intente de nuevo, click en el botón.</div>
                @endif
               
                @include('admin.clientes.partials.modalCrear')
            </div>

            <div class="col-lg-12 mt-4 ">
                <div class="card">
                    <div class="card-body table-responsive">
                        <!-- LISTA DE CLIENTES -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre y Apellido</th>
                                        <th scope="col">RIF O docuemnto</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($clientes))
                                        @foreach ($clientes as $cliente)
                                            <tr>
                                                <td scope="row">{{ $cliente->id }}</td>
                                                <td>{{ $cliente->nombre }}</td>
                                                <td>{{  number_format($cliente->identificacion, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ $cliente->telefono ?? 'No registrado.' }}</td>
                                            
                                                <td>

                                                    @include('admin.clientes.partials.modalEditar')
                                                    @include('admin.clientes.partials.modaldialog')
                                                    @include('admin.clientes.partials.modal')

                                                </td>
                                            </tr>
                                        
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-danger text-center">No hay resultados</td>
                                        </tr>
                                    @endif
                                   
                                    
                                </tbody>
                            </table>
                        <!-- End LISTA DE CLIENTES -->

                        <!-- PAGINACION BLADE -->
                            {{ $clientes->links() }}
                            {{ "Total de clientes registrados: " . $clientes->total() }}
                        <!-- CIERRE PAGINACION BLADE -->
                    </div>
                </div>
            </div>
        </div>
    </section>

 
 

@endsection

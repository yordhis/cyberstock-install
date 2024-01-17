@extends('layouts.app')

@section('title', 'Lista de Categorias')

@section('content')
    @isset($respuesta)
        @include('partials.alert')
    @endisset
    <div id="alert"></div>

    <section class="section">
        <div class="row">

            <div class="col-sm-8">
                <h2>Lista de Activos inmobiliarios</h2>
            </div>
            <div class="col-sm-4 text-end">
                @if($errors->any())
                    <div class="text-danger">No se registro el inmobiliario, click en el botón.</div>
                @endif
                @include('admin.activoInmobiliarios.partials.modalCrear')
            </div>

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">código</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Ubicación</th>
                                        <th scope="col">Fecha de compra</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Costo</th>
                                        <th scope="col">Estatus del activo</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    @foreach ($activoInmobiliarios as $item)
                                        <tr>
                                            <td scope="row">{{ $item->id }}</td>
                                            <td>{{ $item->codigo }}</td>
                                            <td>{{ $item->descripcion }}</td>
                                            <td>{{ $item->ubicacion ?? 'Indefinido' }}</td>
                                            <td>{{ $item->fecha_compra ?? 'Indefinido' }}</td>
                                            <td>{{ $item->cantidad ?? 0 }}</td>
                                            <td>{{ $item->costo ?? 0 }}</td>
                                            <td>{{ $item->estatus ? 'OPERATIVO' : 'NO OPERATIVO' }}</td>
                                            <td>

                                                @include('admin.activoInmobiliarios.partials.modalEditar')
                                                @include('admin.activoInmobiliarios.partials.modal')
  
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->

                        {{ $activoInmobiliarios->links() }}
                        {{ "Total de categoiras: " . $activoInmobiliarios->total() }}

                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

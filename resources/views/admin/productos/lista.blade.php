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

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table datatable ">
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
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($productos as $producto)
                                        <tr>
                                            <th scope="row">{{ $contador }}</th>
                                            <td>{{ $producto->codigo }}</td>
                                            <td id="tdDescripcion">{{ $producto->descripcion }}</td>
                                            <td>{{ $producto->id_categoria->nombre ?? 0 }}</td>
                                            <td>{{ $producto->id_marca->nombre ?? 0 }}</td>
                                           
                                            <td>
                                               
                                                @include('admin.productos.partials.modaldialog')
                                                @include('admin.productos.partials.modal')
                                                @include('admin.productos.partials.modalEditar')
                                                    
                                                
                                            </td>
                                        </tr>
                                        @php $contador++; @endphp
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            {{-- <div class="d-flex justify-content-center">
                                
                                    {!! $productos->links() !!}
                              
                            </div> --}}
                     
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

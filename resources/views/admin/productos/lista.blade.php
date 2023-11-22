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

            
            <div class="col-sm-6 col-xs-12">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="filtro-descripcion" placeholder="Buscar producto por Código o Descripcion" aria-label="Buscar producto" aria-describedby="basic-addon1">
                    <span class="text-danger invalido"></span>
                </div>
            </div>
            

            <div class="col-sm-4 col-xs-12">
                <div class="input-group mb-3">
                    <input type="submit" class="btn btn-success form-control" id="filtro-limpiar" value="Limpiar Filtro" >
                </div>
            </div>

            <div class="col-lg-12 mt-4 ">

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
                                    {{-- @php $contador = 1; @endphp
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
                                    @endforeach --}}
                                    
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <nav class="paginacion" aria-label="Page navigation example"></nav>
                         
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

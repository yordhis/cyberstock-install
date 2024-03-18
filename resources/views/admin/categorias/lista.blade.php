@extends('layouts.app')

@section('title', 'Lista de Categorias')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-8">
                <h2>Lista de Categorias</h2>
            </div>
            <div class="col-sm-4 text-end">
                @include('admin.categorias.partials.modalCrear')
            </div>

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Categoria</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($categorias as $categoria)
                                        <tr>
                                            <td scope="row">{{ $contador }}</td>
                                            <td>{{ $categoria->nombre }}</td>
                                            <td>

                                                @include('admin.categorias.partials.modalEditar')
                                                @include('admin.categorias.partials.modal')
  
                                            </td>
                                        </tr>
                                        @php $contador++; @endphp
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

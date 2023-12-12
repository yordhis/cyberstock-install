@extends('layouts.app')

@section('title', 'Lista de Marca')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-8">
                <h2>Lista de Marcas</h2>
            </div>
            <div class="col-sm-4 text-end">
                @include('admin.marcas.partials.modalCrear')
            </div>

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Marcas</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($marcas as $marca)
                                        <tr>
                                            <td scope="row">{{ $marca->id }}</td>
                                            <td>{{ $marca->nombre }}</td>
                                            <td>

                                                @include('admin.marcas.partials.modalEditar')
                                                @include('admin.marcas.partials.modal')
  
                                            </td>
                                        </tr>
                                     
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->
                            {{ $marcas->links() }}
                            {{ "Total de marcas: " . $marcas->total() }}
                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

@extends('layouts.app')

@section('title', 'Lista de Proveedores')

@section('content')
    
    <div id="alert"></div>

    <section class="section">
        <div class="row">

            <div class="col-sm-8">
                <h2>Lista de Proveedores</h2>
            </div>

            <div class="col-sm-4 text-end">
                @include('admin.proveedores.partials.modalCrear')
            </div>


            <div class="col-lg-12 mt-2">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table datatable ">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Empresa</th>
                                        <th scope="col">Contacto</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($proveedores as $proveedore)
                                        <tr>
                                            <th scope="row">{{ $contador }}</th>
                                            <td>{{ $proveedore->tipo_documento }}-{{ $proveedore->codigo }}</td>
                                            <td>{{ $proveedore->empresa }}</td>
                                            <td>{{ $proveedore->contacto }}</td>
                                            <td>{{ $proveedore->telefono }}</td>
                                            <td>{{ $proveedore->correo }}</td>
    
                                            <td>
                                             
                                                @include('admin.proveedores.partials.modaldialog')
                                                
                                                @include('admin.proveedores.partials.modalEditar')
                                                        
                                                @include('admin.proveedores.partials.modal')
                                                    
                                                
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

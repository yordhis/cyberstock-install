@extends('layouts.app')

@section('title', 'Lista de salidas de inventario')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>Lista de movimientos de salida de inventario</h2>
            </div>
           

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table datatable ">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">N° factura</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($salidas as $factura)
                                        <tr>
                                            <td scope="row">{{ $contador }}</td>
                                            <td>{{ $factura->codigo }}</td>
                                            <td>

                                                {{-- @include('admin.salidas.partials.modalEditar') --}}
                                                @include('admin.salidas.partials.modaldialog')
  
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

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
                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">N° Transacción</th>
                                        <th scope="col">N° Factura</th>
                                        <th scope="col">Razon Social</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Total Articulos</th>
                                        <th scope="col">Concepto</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    @foreach ($salidas as $factura)
                                        <tr>
                                            <td scope="row">{{ $factura->id  }}</td>
                                            <td>{{ $factura->codigo }}</td>
                                            <td>{{ $factura->codigo_factura }}</td>
                                            <td>{{ $factura->cliente[0]['nombre'] }}</td>
                                            <td>{{ $factura->total }}</td>
                                            <td>{{ $factura->totalArticulos }}</td>
                                            <td>{{ $factura->concepto }}</td>
                                            <td>{{ $factura->fecha }}</td>
                                            <td>

                                                {{-- @include('admin.salidas.partials.modalEditar') --}}
                                                @include('admin.salidas.partials.modaldialog')
  
                                            </td>
                                        </tr>
                                      
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->

                        <!-- PAGINACION LARAVEL-->
                        {{ $salidas->links(); }}
                        
                        <!-- Total de facturas pendientes -->
                        {{ "Total de movimientos: " . $salidas->total() }}

                    </div>
                </div>

            </div>



        </div>
    </section>

    
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
  
 

@endsection

@extends('layouts.app')

@section('title', 'Lista de entrada de inventario')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>Lista de movimientos de entrada de inventario</h2>
            </div>
           

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">N° Movimiento</th>
                                        <th scope="col">N° factura</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Total de articulos</th>
                                        <th scope="col">Concepto</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($entradas as $factura)
                                        <tr>
                                            <td scope="row">{{ $contador }}</td>
                                            <td>{{ $factura->codigo }}</td>
                                            <td>{{ $factura->codigo_factura }}</td>
                                            <td>{{ $factura->proveedor[0]->empresa }}</td>
                                            <td>{{ $factura->total }}</td>
                                            <td>{{ $factura->totalArticulos }}</td>
                                            <td>{{ $factura->concepto }}</td>
                                            
                                            <td>
                                                {{-- @include('admin.entradas.partials.modalEditar') --}}
                                                @include('admin.entradas.partials.modaldialog')
                                            </td>
                                        </tr>
                                        @php $contador++; @endphp
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->
                        
                        <!-- PAGINACION LARAVEL-->
                        {{ $entradas->links(); }}
                        
                        <!-- Total de facturas pendientes -->
                        {{ "Total de movimientos: " . $entradas->total() }}
                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

@extends('layouts.app')

@section('title', 'Lista de facturas por pagar')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>Lista de facturas por pagar</h2>
            </div>
           

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">N° Movimiento</th>
                                        <th scope="col">N° Factura</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Total Art.</th>
                                        <th scope="col">Concepto</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($pagar as $factura)
                                        <tr>
                                            <td scope="row">{{ $contador }}</td>
                                            <td>{{ $factura->codigo }}</td>
                                            <td>{{ $factura->codigo_factura }}</td>
                                            <td>
                                                {{ $factura->proveedor[0]->empresa  ?? 'Proveedor'}} <br>
                                                {{ $factura->proveedor[0]->telefono ?? ''}}
                                            </td>
                                            <td>{{ $factura->total }}</td>
                                            <td>{{ $factura->totalArticulos }}</td>
                                            <td>{{ $factura->concepto }}</td>
                                            <td class="text-danger">{{ $factura->concepto == "CREDITO" ? "PENDIENTE" : "PAGADO" }}</td>
                                            
                                            <td>
                                                @include('admin.pagar.partials.formpagar')
                                                @include('admin.entradas.partials.modaldialog')
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

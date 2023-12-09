@extends('layouts.app')

@section('title', 'Lista de facturas por cobrar')

@section('content')
    <div id="alert"></div>
    
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>Lista de facturas por cobrar</h2>
            </div>
           

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">N° FACTURA</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">MONTO</th>
                                        <th scope="col">CANTIDAD ARTC.</th>
                                        <th scope="col">CONCEPTO</th>
                                        <th scope="col">ESTATUS</th>
                                        <th scope="col">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($cobrar as $factura)
                                        <tr>
                                            <td scope="row">{{ $contador }}</td>
                                            <td>{{ $factura->codigo }}</td>
                                            <td>
                                                {{ $factura->cliente[0]->nombre }} <br>
                                                {{ $factura->cliente[0]->telefono }} 
                                            </td>
                                            <td>{{ $factura->total }}</td>
                                            <td>{{ $factura->totalArticulos }}</td>
                                            <td>{{ $factura->concepto }}</td>
                                            <td class="text-danger">{{ $factura->concepto == "CREDITO" ? "PENDIENTE" : "PAGADO" }}</td>
                                            <td>
                                                @include('admin.cobrar.partials.formpagar')
                                                <a href="{{ route('admin.cuentas.por.cobrar.show', $factura->codigo ) }}"  class="btn btn-success ">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $contador++; @endphp
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->
                        
                        <!-- PAGINACION LARAVEL-->
                        {{ $cobrar->links(); }}
                        
                        <!-- Total de facturas pendientes -->
                        {{ "Total de facturas por cobrar: " . $cobrar->total() }}
                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

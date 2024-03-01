@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>Lista de Facturas</h2>
            </div>
           

            <div class="col-lg-12 mt-4 ">
                
                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>

                                        <th scope="col">N° Factura</th>
                                        <th scope="col">Razón social</th>
                                        <th scope="col">Rif o Cédula</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Total BS</th>
                                        <th scope="col">Total Divisas</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                        @foreach ($facturas as $factura)
                                   
                                            <tr>
                                            
                                                <td>{{ $factura->codigo }}</td>
                                                <td>{{ $factura->razon_social }}</td>
                                                <td>{{ number_format($factura->identificacion, 0, ',', '.') }}</td>
                                                <td>{{  date_format(date_create($factura->fecha), 'd-m-Y') }}</td>
                                                <td>Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</td>
                                                <td>REF: {{ number_format($factura->total, 2, ',', '.') }}</td>
                                                <td> 
                                                    <a href="{{ route('admin.facturas.show', $factura->id) }}" >
                                                        <i class="bi bi-eye btn btn-success"></i>
                                                    </a>
                                    
                                                
                                                    @include('admin.facturas.partials.modal')
                                                

                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                    
                                            
                                       
                                   
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->
                        <!-- PAGINACION BLADE -->
                        {{-- {{ $facturas->links() }}
                        {{ "Total de facturas registrados: " . $facturas->total() }} --}}
                    <!-- CIERRE PAGINACION BLADE -->

                    </div>
                </div>

            </div>



        </div>
    </section>

    <script src="{{ asset('js/utilidad/autorizacion.js') }}" defer><script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    
 

@endsection

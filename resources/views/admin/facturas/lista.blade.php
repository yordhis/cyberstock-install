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
                        
                            <table class="table datatable ">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
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
                                    @php $contador = 1; @endphp
                                    @if (count($facturas))
                                        @foreach ($facturas as $factura)
                                            <tr>
                                                <td scope="row">{{ $contador }}</td> 
                                                <td>{{ $factura->codigo }}</td>
                                                <td>{{ $factura->razon_social }}</td>
                                                <td>{{ number_format($factura->identificacion, 0, ',', '.') }}</td>
                                                <td>{{  date_format(date_create($factura->fecha), 'd-m-Y') }}</td>
                                                <td>Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</td>
                                                <td>REF: {{ number_format($factura->total, 2, ',', '.') }}</td>
                                                <td> 
                                                    <a href="facturas/{{ $factura->id }}" target="_self">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    {{-- @include('admin.facturas.partials.modalEditar') --}}
                                                    {{-- @include('admin.facturas.partials.modaldialog') --}}
                                                    {{-- @include('admin.facturas.partials.modal') --}}

                                                </td>
                                            </tr>
                                            @php $contador++; @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-danger">No hay resultados</td>
                                        </tr>
                                    @endif
                                       
                                   
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

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
                        
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">N° Movimiento</th> --}}
                                        <th scope="col">N° FACTURA</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">MONTO</th>
                                        <th scope="col">TOTAL ABONO</th>
                                        <th scope="col">PENDIENTE</th>
                                        <th scope="col">CONCEPTO</th>
                                        <th scope="col">ESTATUS</th>
                                        <th scope="col">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($cobrar as $factura)
                                        <tr>
                                            {{-- <td scope="row">{{ $factura->codigo }}</td> --}}
                                            <td>{{ $factura->codigo_factura }}</td>
                                            <td style="width: 250px">
                                                {{ explode(" ", $factura->cliente[0]->nombre)[0]  }} 
                                                {{ explode(" ", $factura->cliente[0]->nombre)[count(explode(" ", $factura->cliente[0]->nombre))-1]  }} 
                                                <br>
                                                <i class="bi bi-phone-vibrate"></i>
                                                {{ substr($factura->cliente[0]->telefono, 0, 4)  }} -
                                                {{ substr($factura->cliente[0]->telefono, 4, 7)  }} 
                                            </td>
                                            <td>{{ number_format($factura->total, 2 ,',', '.') }} $</td>
                                            <td class="text-success">{{  number_format($factura->total_abono, 2 ,',', '.') }}$</td>
                                            <td class="text-danger">{{ number_format($factura->total - $factura->total_abono, 2 ,',', '.')  }}$  </td>
                                            <td>
                                                @if ($factura->concepto == "VENTA")
                                                    <i class="bi bi-check2-square text-success fs-2"></i>
                                                @else
                                                    {{ $factura->concepto }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ( $factura->concepto == "CREDITO" )
                                                    <button type="button" class="btn btn-outline-danger">PENDIENTE</button>
                                                @elseif(  $factura->concepto == "CONSUMO"  )
                                                    <button type="button" class="btn btn-outline-warning">CONSUMO</button>    
                                                @else
                                                    <button type="button" class="btn btn-outline-success">PAGADO</button>            
                                                @endif
                                            </td>
                                            <td class="d-flex align-items-center" style="width: 100%">

                                                @include('admin.cobrar.partials.formpagar')
                                                @include('admin.cobrar.partials.modaldialog')

                                                <a href="{{ route('admin.cuentas.por.cobrar.show', $factura->id ) }}"  class="btn btn-success ">
                                                    <i class="bi bi-eye fs-4"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $contador++; @endphp
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->
                        
                        <!-- PAGINACION LARAVEL-->
                        {{-- {{ $cobrar->links(); }} --}}
                        
                        <!-- Total de facturas pendientes -->
                        {{-- {{ "Total de facturas por cobrar: " . $cobrar->total() }} --}}
                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
    {{-- <script src=" {{ asset('js/main.js') }}"></script> --}}
    {{-- <script src=" {{ asset('js/cobrar/index.js') }}" defer></script> --}}


@endsection

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
                    <div class="card-header">
                        <form action="{{route('admin.facturas.index')}}" method="GET" id="filtro" >
                            @csrf
                            @method('GET')
                                <div class="input-group mb-3">
                                
                                    <div class="form-floating ">
                                        <input type="text" class="form-control" id="floatingInput" 
                                        name="filtro"
                                        value="{{ $request->filtro ?? ''}}"
                                        placeholder="Ingrese código, rif o nombre" required>
                                        <label for="floatingInput">Buscar</label>
                                    </div>
                                    
                                    <div class="form-floating">
                                        <select class="form-select" id="floatingSelect"
                                        name="campo" 
                                        aria-label="Floating label select example">
                                            @if ($request->campo)
                                                <option value="{{ $request->campo }}" selected> 
                                                    @switch($request->campo)
                                                        @case('codigo')
                                                                Codigo de factura
                                                            @break
                                                        @case('identificacion')
                                                                Rif o cédula del cliente
                                                            @break
                                                        @case('razon_social')
                                                                Por nombre de cliente
                                                            @break
                                                        @default
                                                            
                                                    @endswitch
                                                </option>
                                            @else
                                                <option value="codigo" selected>Codigo de factura</option>
                                            @endif
                                            
                                            <option value="codigo">Codigo de factura</option>
                                            <option value="identificacion">Rif o cédula del cliente</option>
                                            <option value="razon_social">Por nombre de cliente</option>
                                        </select>
                                        <label for="floatingSelect">Seleccione el tipo de filtro</label>
                                    </div>
                                    
                                    
                                        <button type="submit" class="btn btn-primary input-group-text"
                                        id="inputGroup-sizing-default">
                                            <i class="bi bi-search"></i>
                                        </button>
                                  
                                </div>  
                            </form>
                          
                    </div>
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th scope="col">N° Factura</th>
                                        <th scope="col">Razón social</th>
                                        <th scope="col">Rif o Cédula</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Total BS</th>
                                        <th scope="col">Total Divisas</th>
                                        <th scope="col">CONCEPTO</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                        @foreach ($facturas as $factura)
                                   
                                            <tr class="{{ $factura->concepto == "ANULADA" ? 'table-danger' : '' }}">
                                            
                                                <td>{{ $factura->codigo }}</td>
                                                <td>{{ $factura->razon_social }}</td>
                                                <td>{{ $factura->identificacion }}</td>
                                                <td>{{  date_format(date_create($factura->fecha), 'd-m-Y') }}</td>
                                                <td>Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</td>
                                                <td>REF: {{ number_format($factura->total, 2, ',', '.') }}</td>
                                                <td>
                                                    @if ( $factura->concepto == "CREDITO" )
                                               
                                                        <a href="{{ route('admin.cuentas.por.cobrar.index') }}" class="btn btn-outline-danger">PENDIENTE</a>
                                                    
                                                    @elseif(  $factura->concepto == "VENTA"  )
                                                        <button type="button" class="btn btn-outline-success">PAGADO</button>    
                                                
                                                    @elseif(  $factura->concepto == "ANULADA"  )
                                                        <button type="button" class="btn btn-outline-dark">{{ $factura->concepto }}</button>    
                                                    @else
                                                        <button type="button" class="btn btn-outline-success">PAGADO</button>            
                                                    @endif
                                                </td>
                                                <td> 

                                                    @if ( $factura->concepto != "ANULADA"  )
                                                        <a href="{{ route('admin.facturas.show', $factura->id) }}" >
                                                            <i class="bi bi-eye btn btn-success"></i>
                                                        </a>
                                                    @endif
                                    
                                                
                                                    @include('admin.facturas.partials.modal')
                                                

                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                    
                                            
                                       
                                   
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->
                        <!-- PAGINACION BLADE -->
                        {{ $facturas->appends(["filtro" => $request->filtro, "campo" => $request->campo])->links() }}
                        {{ "Total de facturas registrados: " . $facturas->total() }}
                    <!-- CIERRE PAGINACION BLADE -->

                    </div>
                </div>

            </div>



        </div>
    </section>

    <script src="{{ asset('js/utilidad/autorizacion.js') }}" defer><script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    
 

@endsection

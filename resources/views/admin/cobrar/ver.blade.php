@extends('layouts.app')

@section('title', 'Factura por cobrar')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>Factura por cobrar</h2>
            </div>

            <section class="section profile">
                <div class="row">
                <div class="col-2"></div>
                  <div class="col-xl-8">
    
                    <div class="card">
                      <div class="card-header text-black">
                        <div class="text-center">
                          <img src="{{ $pos->imagen }}" class="img w-25 h-50"  alt="">
                          {{-- <p class="p-0 fs-5">{{ $factura->iva > 0 ? "SENIAT" : '' }}</p> --}}
                          <p class="p-0 m-0 fs-6">{{ $pos->rif ?? '' }}</p>
                          <p class="p-0 m-0 fs-6">{{ $pos->empresa ?? '' }}</p>
                          <p class="p-0 m-0 fs-6">{{ $pos->direccion ?? '' }}</p>
                          <p class="p-0 m-0 fs-6">ZONA POSTAL {{ $pos->postal ?? '' }}</p>
                        </div>

                      </div>
                      <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                      
                          
                          <p class="text-center p-0 m-0">FACTURA</p>
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight"><b>N° Factura: </b></div>
                            <div class="p-2 bd-highlight" id="codigoFactura">{{ $factura->codigo_factura }}</div>
                          </div>
                          
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight"><b> Cliente: </b> {{ $factura->cliente[0]->nombre ?? '' }} </div>
                          </div>
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight"><b>RIF: </b> {{  $factura->cliente[0]->tipo . "-" . $factura->cliente[0]->identificacion }}</div>
                          </div>

                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight"><b>Fecha:</b> {{ date_format(date_create( $factura->fecha ), "d-m-Y")  }}</div>
                            <div class="p-2 bd-highlight"><b>Hora:</b> {{ date_format(date_create( $factura->fecha), "h:i:s") }}</div>
                            
                          </div>
    
                          <div class="bg-black m-1 w-100" style="height: 2px;"></div>
                          
                          <div class="d-flex justify-content-between w-100 m-0 p-0 ">
                            <div class="p-2 bd-highlight" > <b>CANTIDAD X PRODUCTO</b> </div>
                            <div class="p-2 bd-highlight" > <b>C/U</b> </div>
                            <div class="p-2 bd-highlight" > <b>SUBTOTAL</b> </div>
                          </div>
                          <div class="bg-black m-1 w-100" style="height: 1px;"></div>

                          @foreach ($factura->carrito as $producto)
                            <div class="d-flex justify-content-between w-100 m-0 p-0" style="margin: 0%;  padding: 0%;">
                              <div class="p-2 bd-highlight" > {{ $producto->cantidad }} X {{ $producto->descripcion }} </div>
                              <div class="p-2 bd-highlight" >{{ number_format($producto->costo *  $factura->tasa, 2, ',', '.') }} $</div>
                              <div class="p-2 bd-highlight" >{{ number_format($producto->subtotal *  $factura->tasa, 2, ',', '.') }} $</div>
                            </div>
                          @endforeach
    
                          <div class="bg-black m-1 w-100" style="height: 2px;"></div>

                         
    
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">
                              SUBTOTAL: <br>
                              |Total de Articulos: {{ $factura->totalArticulos }} |
                            </div>
                            <div class="p-2 bd-highlight">{{ number_format($factura->subtotal *  $factura->tasa, 2, ',', '.') }} $</div>
                          </div>
    

                          {{-- <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">TOTAL SIN IVA:</div>
                            <div class="p-2 bd-highlight">Bs {{ number_format($factura->subtotal *  $factura->tasa, 2, ',', '.') }}</div>
                          </div> --}}
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">
                              Descuento: {{ $factura->descuento }}%
                             
                            </div>
                            <div class="p-2 bd-highlight">{{ number_format($factura->descuento *  $factura->tasa, 2, ',', '.') }} $</div>
                          </div>
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">IVA:</div>
                            @php
                                $subtotal = $factura->subtotal * $factura->tasa * ($factura->iva);
                            @endphp
                            <div class="p-2 bd-highlight"> {{ number_format($subtotal, 2, ',', '.') }} $</div>
                          </div>
                          
    
                          <div class="bg-black m-1 w-100" style="height: 2px;"></div>

                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">TOTAL:</div>
                            <div class="p-2 bd-highlight">{{ number_format($factura->total * $factura->tasa, 2, ',', '.') }} $</div>
                          </div>

                          <div class="w-100" id="metodosPagos">
                              {{-- <div class="p-2 bd-highlight">tipo de pago</div>
                              <div class="p-2 bd-highlight">Bs 100</div> --}}
                          </div>

                          <div class="d-flex justify-content-between w-100 m-0 p-0" id="vuelto">
                              {{-- <div class="p-2 bd-highlight">tipo de pago</div>
                              <div class="p-2 bd-highlight">Bs 100</div> --}}
                          </div>
                          
                          <div class="d-flex justify-content-between w-100 m-2 p-0">
                            <a href="{{ route('admin.cuentas.por.cobrar.index')}}" class="col-sm-6 col-xs-12  btn btn-outline-danger pt-2" target="_self">VOLVER A LISTA</a>
                            {{-- <button type="button" class="col-sm-4 col-xs-12 ms-2 btn btn-outline-warning">
                              <i class="bx bx-pencil"></i>
                              REALIZAR DEVOLUCIÓN
                            </button> --}}
                            {{-- <button type="button" class="col-sm-3 col-xs-12 ms-2 btn btn-outline-danger">
                              <i class="bx bx-trash"></i>
                              ELIMINAR
                            </button> --}}
                            <button type="button" class="col-sm-6 col-xs-12 ms-2 btn btn-outline-success acciones-factura" id="{{ $factura->codigo }}">
                              <i class="bx bx-printer"></i>
                              IMPRIMIR

                             <span id="cargando"></span>
                            </button>
                          <div>
                      </div>
                    </div>
          
                  </div>
                </div>
              </section>
           



        </div>
    </section>

    
    <script src="{{ asset('/js/main.js') }}" defer></script>
    <script src="{{ asset('/js/facturas/facturaController.js') }}" defer></script>
    <script src="{{ asset('/js/facturas/ver.js') }}" defer></script>

@endsection

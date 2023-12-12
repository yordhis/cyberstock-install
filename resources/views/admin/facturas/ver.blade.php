@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>Factura</h2>
            </div>

            <section class="section profile">
                <div class="row">
                <div class="col-2"></div>
                  <div class="col-xl-8">
    
                    <div class="card">
                      <div class="card-header text-black">
                        <div class="text-center">
                          <img src="{{ $pos->imagen }}" class="img w-50 h-50"  alt="">
                          {{-- <p class="p-0 fs-5">{{ $factura->iva > 0 ? "SENIAT" : '' }}</p> --}}
                          <p class="p-0 m-0 fs-6">{{ $pos->rif ?? '' }}</p>
                          <p class="p-0 m-0 fs-6">{{ $pos->empresa ?? '' }}</p>
                          <p class="p-0 m-0 fs-6">{{ $pos->direccion ?? '' }}</p>
                          <p class="p-0 m-0 fs-6">ZONA POSTAL {{ $pos->postal ?? '' }}</p>
                        </div>
                        <span class="text-start ">Cliente: </span> {{ $factura->razon_social ?? '' }} <br>
                        <span class="text-start ">RIF:</span> {{ number_format($factura->identificacion, 0, ',', '.') ?? '' }} <br>
                      </div>
                      <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        {{-- <img src="{{ $factura->imagen }}" alt="Profile" class="rounded-circle">
                        <h2>  {{ $factura->descripcion }}</h2> --}}
                          
                          <p class="text-center p-0 m-0">FACTURA</p>
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight"><b>N° Factura: </b></div>
                            <div class="p-2 bd-highlight" id="codigoFactura">{{ $factura->codigo }}</div>
                          </div>
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">Fecha: {{ date_format(date_create( explode( " ",$factura->created_at)[0] ), "d-m-Y")  }}</div>
                            <div class="p-2 bd-highlight">Hora: {{ date_format(date_create( explode( " ",$factura->created_at)[1] ), "h:i:s") }}</div>
                            
                          </div>
    
                          <div class="bg-black m-1 w-100" style="height: 2px;"></div>
                          <!-- Productos -->
                    
                          @foreach ($factura->carrito as $producto)
                            <div class="d-flex justify-content-between w-100 m-0 p-0" style="margin: 0%;  padding: 0%;">
                              <div class="p-2 bd-highlight" > {{ $producto->cantidad }} X {{ $producto->descripcion }} </div>
                              <div class="p-2 bd-highlight" >Bs {{ floatVal($producto->subtotal) *  $factura->tasa }}</div>
                            </div>
                            
                          @endforeach
    
                          <div class="bg-black m-1 w-100" style="height: 2px;"></div>

                         
    
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">
                              SUBTOTAL: <br>
                              |Total de Articulos: {{ $factura->totalArticulos }} |
                            </div>
                            <div class="p-2 bd-highlight">Bs {{ number_format($factura->subtotal *  $factura->tasa, 2, ',', '.') }}</div>
                          </div>
    

                          {{-- <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">TOTAL SIN IVA:</div>
                            <div class="p-2 bd-highlight">Bs {{ number_format($factura->subtotal *  $factura->tasa, 2, ',', '.') }}</div>
                          </div> --}}
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">
                              Descuento: {{ $factura->descuento }}%
                             
                            </div>
                            <div class="p-2 bd-highlight">Bs {{ number_format($factura->descuento *  $factura->tasa, 2, ',', '.') }}</div>
                          </div>
                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">IVA:</div>
                          
                            <div class="p-2 bd-highlight">Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</div>
                          </div>
                          
    
                          <div class="bg-black m-1 w-100" style="height: 2px;"></div>

                          <div class="d-flex justify-content-between w-100 m-0 p-0">
                            <div class="p-2 bd-highlight">TOTAL:</div>
                            <div class="p-2 bd-highlight">Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</div>
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
                            <a href="{{ route('admin.facturas.index') }}" class="col-sm-6 col-xs-12  btn btn-outline-danger pt-2" target="_self">VOLVER A LISTA</a>
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

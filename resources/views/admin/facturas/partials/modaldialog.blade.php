 <!-- Modal Dialog Scrollable -->
 <a type="button" class="" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$factura->id}}">
    <i class="bi bi-eye"></i>
 </a>
  <div class="modal fade" id="modalDialogScrollable{{$factura->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Informacion de factura</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-header text-black">
                    <div class="text-center">
                      <p class="p-0 fs-5">{{ $factura->iva > 0 ? "SENIAT" : '' }}</p>
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
                        <div class="p-2 bd-highlight">{{ $factura->codigo }}</div>
                      </div>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">Fecha: {{ date_format(date_create( explode( " ",$factura->created_at)[0] ), "d-m-Y")  }}</div>
                        <div class="p-2 bd-highlight">Hora: {{ date_format(date_create( explode( " ",$factura->created_at)[1] ), "h:i:s") }}</div>
                        
                      </div>

                      <p>------------------------------------------------------------------------------</p>
                      <!-- Productos -->
                      @foreach ($factura->carrito as $producto)
                        <div class="d-flex justify-content-between w-100 m-0 p-0" style="margin: 0%;  padding: 0%;">
                          <div class="p-2 bd-highlight" > {{ $producto->cantidad }} X {{ $producto->descripcion }} </div>
                          <div class="p-2 bd-highlight" >Bs {{ number_format($producto->subtotal *  $factura->tasa, 2, ',', '.') }}</div>
                        </div>
                      @endforeach

                      <p>------------------------------------------------------------------------------</p>

                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">
                          SUBTIL: <br>
                          |Total de Articulos: {{ $factura->total_articulos }} |
                        </div>
                        <div class="p-2 bd-highlight">Bs {{ number_format($factura->subtotal *  $factura->tasa, 2, ',', '.') }}</div>
                      </div>

                      <p>------------------------------------------------------------------------------</p>

                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">TOTAL SIN IVA:</div>
                        <div class="p-2 bd-highlight">Bs {{ number_format($factura->subtotal *  $factura->tasa, 2, ',', '.') }}</div>
                      </div>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">IVA:</div>
                        <div class="p-2 bd-highlight">Bs {{ number_format($factura->subtotal * $factura->tasa * $utilidades[0]->iva['restar'], 2, ',', '.') }}</div>
                      </div>

                      <p>------------------------------------------------------------------------------</p>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">TOTAL:</div>
                        <div class="p-2 bd-highlight">Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</div>
                      </div>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">EFECTIVO 3</div>
                        <div class="p-2 bd-highlight">Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</div>
                      </div>

                  
                  </div>
                </div>
      
              </div>
            </div>
          </section>
          
          
            
          


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

          <a href="#" id="imprimirFactura" class="btn btn-primary " target="_self" rel="" >
            IMPRIMIR
          </a>
          
        </div>
      </div>
    </div>
  </div><!-- End Modal Dialog Scrollable-->
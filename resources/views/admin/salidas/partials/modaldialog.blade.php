 <!-- Modal Dialog Scrollable -->
 <a type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$factura->id}}">
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
                      @if ($pos->estatusImagen)
                        <img src="{{$pos->imagen}}"  class="img w-50 h-50">  
                      @endif
                      {{-- <p class="p-0 fs-5">{{ $factura->iva > 0 ? "SENIAT" : '' }}</p> --}}
                      <p class="p-0 m-0 fs-6">{{ $pos->rif ?? '' }}</p>
                      <p class="p-0 m-0 fs-6">{{ $pos->empresa ?? '' }}</p>
                      <p class="p-0 m-0 fs-6">{{ $pos->direccion ?? '' }}</p>
                      <p class="p-0 m-0 fs-6">ZONA POSTAL {{ $pos->postal ?? '' }}</p>
                    </div>
          
                  </div>
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                 
                      
                      <p class="text-center p-0 m-0 fs-4">{{  $factura->iva ? 'FACTURA' : 'NOTA DE ENTREGA' }} </p>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight"><b>N° Factura: </b></div>
                        <div class="p-2 bd-highlight">{{ $factura->codigo }}</div>
                      </div>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight"><b>Concepto: </b></div>
                        <div class="p-2 bd-highlight">{{ $factura->concepto }}</div>
                      </div>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight"><b>Cliente: </b> {{ $factura->razon_social ?? '' }}</div>
                      </div>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight"><b>RIF: </b>{{ $factura->identificacion }}</div>
                      </div>
                  
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight"><b>Fecha: </b> {{ date_format(date_create( $factura->fecha) , "d-m-Y")  }}</div>
                        <div class="p-2 bd-highlight"><b>Hora: </b> {{ date_format(date_create( $factura->fecha), "h:i:s") }}</div>
                      </div>

                      <p>------------------------------------------------------------------------------</p>
                      <div class="d-flex justify-content-between w-100 m-0 p-0 ">
                        <div class="p-2 bd-highlight" > <b>CANTIDAD X PRODUCTO</b> </div>
                        <div class="p-2 bd-highlight" > <b>C/U</b> </div>
                        <div class="p-2 bd-highlight" > <b>SUBTOTAL</b> </div>
                      </div>
                      <p>------------------------------------------------------------------------------</p>
                      <!-- Productos -->
                      @foreach ($factura->carrito as $producto)
                        <div class="d-flex justify-content-between w-100 m-0 p-0" style="margin: 0%;  padding: 0%;">
                          <div class="p-2 bd-highlight" > {{ $producto->cantidad }} X {{ $producto->descripcion }} </div>
                          <div class="p-2 bd-highlight" >{{ number_format($producto->costo, 2, ',', '.') }} USD</div>
                          <div class="p-2 bd-highlight" > {{ number_format($producto->subtotal, 2, ',', '.') }} USD</div>
                        </div>
                      @endforeach

                      <p>------------------------------------------------------------------------------</p>

                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">
                          <b>SUBTOTAL:</b> <br>
                          |Total de Articulos: {{ $factura->totalArticulos }} |
                        </div>
                        <div class="p-2 bd-highlight"> {{ number_format($factura->subtotal, 2, ',', '.') }} USD</div>
                      </div>
                      
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight"><b> IVA: </b></div>
                        <div class="p-2 bd-highlight"> {{ number_format($factura->subtotal * $factura->iva, 2, ',', '.') }} USD</div>
                      </div>

                      <p>------------------------------------------------------------------------------</p>

                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight"><b> TOTAL: </b></div>
                        <div class="p-2 bd-highlight"> {{ number_format($factura->total, 2, ',', '.') }} USD</div>
                      </div>
                   
                      <p>------------------------------------------------------------------------------</p>
                      <div class="d-flex justify-content-between w-100 m-0 p-0">
                        <div class="p-2 bd-highlight">
                          <b>OBSERVACIÓN DE {{ $factura->concepto }}</b>
                        </div>
                        <div class="p-2 bd-highlight">
                          {{ $factura->observacion ?? 'No hay observación'}}
                        </div>
                      </div>
                 

                  
                  </div>
                </div>
      
              </div>
            </div>
          </section>
          
          
            
          


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>

          {{-- <a href="pos/imprimirFactura/{{$factura->codigo}}" target="_blank" rel="noopener noreferrer">
            IMPRIMIR
          </a> --}}
          
        </div>
      </div>
    </div>
  </div><!-- End Modal Dialog Scrollable-->
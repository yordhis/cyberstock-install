 <!-- Modal Dialog Scrollable -->
 <a type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$producto->id}}">
    <i class="bi bi-eye"></i>
 </a>
  <div class="modal fade" id="modalDialogScrollable{{$producto->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Informacion del producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ $producto->imagen }}" alt="Profile" class="rounded-circle">
                    <h2>  {{ $producto->descripcion }}</h2>
             
                    
                      <img alt='Barcode Generator TEC-IT & Cyber Staff, C.A.'
                      src="https://barcode.tec-it.com/barcode.ashx?data={{ $producto->codigo }}&code=Code128"
                      id="generadorDeCodigoDeBarra"
                      >

                      <button class="btn btn-primary m-2 button__print__barcode"  id="{{ $producto->codigo }}" >Generar PDF de códigos de barra</button>
                   

                    

                    <div class="container-fluid">
                      <div class="row">
                        <hr>
                        <div class="col-md-12">
                          <strong>Mas detalles</strong>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Marca: </span> {{ $producto->marca  }} <br>
                          <span class="text-primary">Categoria: </span> {{ $producto->categoria  }} <br>
                        </div>

                        <div class="col-md-12 label"> 
                          
                          @empty($producto->creado)
                            <span class="text-primary">Fecha de creación:</span> 
                            {{ isset($producto->creado) ? date_format(date_create($producto->creado), "d-m-Y") : "" }}
                          @endempty
                        </div>     
                      </div>
                    </div>
                  </div>
                </div>
      
              </div>
            </div>
          </section>
          
          
            
          


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div><!-- End Modal Dialog Scrollable-->
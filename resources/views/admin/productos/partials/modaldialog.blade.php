 <!-- Modal Dialog Scrollable -->
 <a type="button" class="" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$producto->id}}">
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
                    <h3><b>Código de barra: </b>{{ $producto->codigo }}
                    
                   

                    

                    <div class="container-fluid">
                      <div class="row">
                        <hr>
                        <div class="col-md-12">
                          <h3>Mas detalles</h3>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Marca: </span> {{ $producto->id_marca->nombre  }} <br>
                          <span class="text-primary">Categoria: </span> {{ $producto->id_categoria->nombre  }} <br>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Fecha de creación:</span>  {{ date_format(date_create($producto->created_at), "d-m-Y h:m") }}
                          @empty($producto->created_at)
                          {{ isset($producto->created_at) ? date_format(date_create($producto->created_at), "d-m-Y") : "" }}
                          
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
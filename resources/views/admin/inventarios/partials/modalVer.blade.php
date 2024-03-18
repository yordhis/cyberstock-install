 <!-- Modal Dialog Scrollable -->
 <a type="button" class="" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$inventario->id}}">
    <i class="bi bi-eye"></i>
 </a>
  <div class="modal fade" id="modalDialogScrollable{{$inventario->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Informacion de inventario del producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ $inventario->imagen }}" alt="Profile" class="rounded-circle">
                    <h2>  {{ $inventario->descripcion }}</h2>
                    <h3><b>Código de barra: </b>{{ $inventario->codigo }} </h3>

                    <div class="container-fluid">
                      <div class="row">
                        <hr>
                        <div class="col-md-12">
                          <h3>Mas detalles</h3>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Costo: REF</span> {{ doubleval($inventario->costo) }} <br>
                          <span class="text-primary">Costo: Bs</span> {{ doubleval($inventario->costo)  * $utilidades[0]->tasa}} <br>
                          <span class="text-primary">PVP: REF</span> {{ doubleval($inventario->pvp) }} <br>
                          <span class="text-primary">PVP:Bs</span> {{ doubleval($inventario->pvp) * $utilidades[0]->tasa }} 
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Cantidad en existencia:</span> {{ $inventario->cantidad }} 
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Categoria:</span> {{ $inventario->id_categoria->nombre }} <br>
                          <span class="text-primary">Marca:</span> {{ $inventario->id_marca->nombre }}
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Fecha de entrada:</span> {{ $inventario->fecha_entrada ?? ''}}
                          
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
        </div>
      </div>
    </div>
  </div><!-- End Modal Dialog Scrollable-->
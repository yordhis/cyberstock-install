 <!-- Modal Dialog Scrollable -->
 <a type="button" class="" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$proveedore->id}}">
    <i class="bi bi-eye text-info"></i>
 </a>
  <div class="modal fade" id="modalDialogScrollable{{$proveedore->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Información del proveedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ $proveedore->imagen }}" alt="Profile" class="rounded-circle">
                    <h2>  {{ $proveedore->empresa }}</h2>
                    

                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <hr>
                          <h3>Datos Empresariales</h3>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Código o Documento:</span> {{ $proveedore->codigo }} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Empresa:</span> {{ $proveedore->empresa }} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Rubro:</span> {{ $proveedore->rubro }} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Dirección de domicilio:</span> {{ $proveedore->direccion }}
                        </div>


                        <div class="col-md-12">
                          <hr>
                          <h3>Datos De Contacto</h3>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Contacto:</span> {{ $proveedore->contacto }} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Teléfono:</span> {{ $proveedore->telefono }} 
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Correo:</span> {{ $proveedore->correo }}
                        </div>
                        
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Fecha de nacimiento:</span> 
                          {{ date_format(date_create($proveedore->nacimiento), "d-m-Y") }}
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Edad:</span> {{ $proveedore->edad }} Años
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
 <!-- Modal Dialog Scrollable -->
 <a type="button" class="" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$cliente->id}}">
    <i class="bi bi-eye"></i>
 </a>
  <div class="modal fade" id="modalDialogScrollable{{$cliente->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Informacion del cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    {{-- <img src="{{ $producto->imagen }}" alt="Profile" class="rounded-circle"> --}}
                    <h2>  {{ $cliente->nombre }}</h2>
                    {{-- <h3><b>Código de barra: </b>{{ $producto->codigo }} </h3> --}}

                    <div class="container-fluid">
                      <div class="row">
                        <hr>
                        <div class="col-md-12">
                          <h3>Mas detalles</h3>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Nombre y Apellido: </span> {{ $cliente->nombre }} <br>                       
                          <span class="text-primary">RIF o documento:</span> {{ $cliente->tipo }} - {{ number_format($cliente->identificacion, 0, ',', '.')}}<br>
                          <span class="text-primary">Teléfono: </span> {{ $cliente->telefono}} <br>
                          <span class="text-primary">Correo: </span> {{ $cliente->correo }} <br>
                          <span class="text-primary">Dirección: </span> {{ $cliente->direccion }} <br>

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
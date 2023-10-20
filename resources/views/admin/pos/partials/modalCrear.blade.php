<!-- Vertically centered Modal -->
<button type="button" class="nav-item text-green bg-primary p-2 rounded-2 " data-bs-toggle="modal"
    data-bs-target="#modalCrear" >
    <i class="bi bi-filter-square "></i> Agregar categoria
</button>



<div class="modal fade {{ isset($request->codigo) ? "show" : ""}} " style="{{ isset($request->codigo) ? "display: block;" : "display: none;"}}" id="modalCrear" tabindex="-1"  >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Crear Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="productos/categorias" method="post" target="_self" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('post')

                <div class="modal-body">
                    <div class="row">
                        {{-- inputs nombre dela categoria --}}
                        <div class="col-12 text-start ">
                            <label for="yourUsername" class="form-label">Nombre de la categoria</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-barcode-reader fs-3"></i>
                                </span>
    
                                <input type="text" name="nombre" class="form-control" id="yourUsername"
                                    placeholder="Ingrese nombre de la categoria" value="{{ $request->nombre ?? '' }}" required>
                                <div class="invalid-feedback">Por favor ingrese El Código de barra! </div>
                            </div>
                        </div>
                    </div>

                </div> <!--Fin div body-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary ">Guardar Datos</button>
                </div>
            </form>
        </div>
    </div>




</div><!-- End Vertically centered Modal-->

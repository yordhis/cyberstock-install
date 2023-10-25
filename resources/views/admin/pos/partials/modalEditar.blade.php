
        
<!-- Vertically centered Modal -->
{{-- <a type="button" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEditarTasa">
    <i class="bi bi-pencil "></i>
</a> --}}

<button type="button" class="btn btn-primary  mt-4 mb-2 me-2  " data-bs-toggle="modal"
    data-bs-target="#modalEditarTasa" >
    <i class="bi bi-pencil "></i> Actualizar tasa
</button>
    


<div class="modal fade" id="modalEditarTasa" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Editar TASA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
     
            <div class="modal-body">
                <form action="updateTasa/{{$utilidades[0]->id}}" method="post" target="_self" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('put')

                <div class="modal-body">
                    <div class="row">
                        {{-- inputs nombre de la categoria --}}
                        <div class="col-12 text-start ">
                            <label for="yourUsername" class="form-label">Nueva Tasa</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-barcode-reader fs-3"></i>
                                </span>
    
                                <input type="number" name="tasa" class="form-control" id="tasa"
                                    placeholder="Ingrese tasa" step="any" value="{{ $utilidades[0]->tasa ?? '' }}" required>
                                <div class="invalid-feedback">Por favor, ingrese tasa! </div>
                            </div>
                        </div>
                    </div>

                </div> <!--Fin div body-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary ">Actualizar tasa</button>
                </div>
            </form>
            </div>
            

    </div>
    </div>

  

    
</div><!-- End Vertically centered Modal-->





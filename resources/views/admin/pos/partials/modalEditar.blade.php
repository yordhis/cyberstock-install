
        
<!-- Vertically centered Modal -->
<a type="button" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria{{$categoria->id}}">
    <i class="bi bi-pencil "></i>
</a>
    


<div class="modal fade" id="modalEditarCategoria{{$categoria->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Editar Marca</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
     
            <div class="modal-body">
                <form action="productos/categorias/{{$categoria->id}}" method="post" target="_self" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('put')

                <div class="modal-body">
                    <div class="row">
                        {{-- inputs nombre de la categoria --}}
                        <div class="col-12 text-start ">
                            <label for="yourUsername" class="form-label">Nombre de la categoria</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-barcode-reader fs-3"></i>
                                </span>
    
                                <input type="text" name="nombre" class="form-control" id="yourUsername"
                                    placeholder="Ingrese nombre de la categoria" value="{{ $categoria->nombre ?? '' }}" required>
                                <div class="invalid-feedback">Por favor, ingrese El nombre de la categoria! </div>
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
    </div>

  

    
</div><!-- End Vertically centered Modal-->




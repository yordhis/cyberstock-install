
        
<!-- Vertically centered Modal -->
<a type="button" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalObservacion{{$inventario->id}}">
    <i class="bi bi-pencil fs-3"></i>
</a>
    


<div class="modal fade" id="modalObservacion{{$inventario->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Asignar Observación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="inventarios/{{$inventario->id}}" method="post" target="_self">
            @csrf
            @method('put')
            <div class="modal-body">
                <div class="col-12">
                    
                    <label for="observacion" class="form-label">Actualizar datos de inventario</label>
                    
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary ">Guardar datos</button>
            </div>
        </form>
    </div>
    </div>

  

    
</div><!-- End Vertically centered Modal-->





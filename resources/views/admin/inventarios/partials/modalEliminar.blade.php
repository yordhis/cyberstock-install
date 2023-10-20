
        
<!-- Vertically centered Modal -->
<a type="button" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar_{{$inventario->id}}">
    <i class="bi bi-trash "></i>
</a>
    


<div class="modal fade" id="modalEliminar_{{$inventario->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Eliminar Datos de Inscripción</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="inventarios/{{$inventario->id}}" method="post" target="_self">
            @csrf
            @method('delete')
            <div class="modal-body">
                <p>
                   Está seguro que desea eliminar los datos de inventario del producto <b>{{$inventario->descripcion}}</b> <br>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Si, Proceder a eleminar</button>
            </div>
        </form>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->



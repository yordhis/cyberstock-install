
        
<!-- Vertically centered Modal -->
<a type="button" class=" fs-3 mb-3" data-bs-toggle="modal" data-bs-target="#verticalycentered{{$usuario->id}}">
    <i class="bi bi-trash text-primary"></i>
</a>

<div class="modal fade" id="verticalycentered{{$usuario->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Eliminando</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Esta seguro que desea eliminar al usuario <span class="text-danger fs-5">{{$usuario->nombre}}</span>? 
        </div>
        <div class="modal-footer">
            <form action="usuarios/{{$usuario->id}}" method="post" target="_self">
            @csrf
            @method('delete')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Si, proceder a eliminar.</button>
            </form>
        </div>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->



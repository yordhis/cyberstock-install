
        
<!-- Vertically centered Modal -->
<a type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-target="#verticalycentered{{$producto->id}}">
    <i class="bi bi-trash"></i>
</a>

<div class="modal fade" id="verticalycentered{{$producto->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Eliminando</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Esta seguro que desea eliminar el producto <span class="text-danger fs-5">{{$producto->descripcion}}</span>? 
        </div>
        <div class="modal-footer">
            <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="post" >
            @csrf
            @method('delete')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Si, proceder a eliminar.</button>
            </form>
        </div>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->



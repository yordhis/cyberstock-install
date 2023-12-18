
        
<!-- Vertically centered Modal -->
<a type="button" class="text-danger" data-bs-toggle="modal" data-bs-target="#verticalycentered{{$factura->id}}">
    <i class="bi bi-trash btn btn-danger"></i>
</a>

<div class="modal fade" id="verticalycentered{{$factura->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Eliminando</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Esta seguro que desea eliminar la factura  de venta del cliente: <span class="text-danger fs-5">{{$factura->razon_social ?? 'Cliente'}}</span>? 
        </div>
        <div class="modal-footer">
            <form action="{{ route('admin.inventarios.eliminarFacturaInventario', $factura->codigo) }}" method="post">
            @csrf
            @method('delete')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Si, proceder a eliminar.</button>
            </form>
        </div>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->



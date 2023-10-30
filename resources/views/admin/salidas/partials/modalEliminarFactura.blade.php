
        
<!-- Vertically centered Modal -->
<a type="button" class="text-white btn btn-danger h-25 mt-4 mb-2 me-2" data-bs-toggle="modal" data-bs-target="#modalElimianrFactura">
    <i class="bi bi-trash"></i> Elimianar factura
</a>

<div class="modal fade" id="modalElimianrFactura" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Eliminando</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Esta seguro que desea eliminar la factura <span class="text-danger fs-5">{{$carritos[0]->codigo ?? ''}}</span>? 
        </div>
        <div class="modal-footer">
            <form action="eliminarCarritoInventarioCompleto/{{$carritos[0]->codigo ?? 0}}" method="post" target="_self">
            @csrf
            @method('delete')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Si, proceder a eliminar factura.</button>
            </form>
        </div>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->




        
<!-- Vertically centered Modal -->
<a  class="text-white" data-bs-toggle="modal" data-bs-target="#verticalycentered{{$factura->id}}">
    <i class="bi bi-trash btn btn-danger"></i>
</a>

<div class="modal fade" id="verticalycentered{{$factura->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Eliminando factura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Esta seguro que desea eliminar la factura del cliente: <span class="text-danger fs-5">{{$factura->razon_social}}</span>? 
        </div>
        <div class="modal-footer">
            <form action="{{ route('admin.facturas.destroy', $factura->id)}}" method="post" target="_self">
            @csrf
            @method('delete')
               
                <label for="clave{{$factura->id}}">Clave de administrador</label>
                <input type="password" autocomplete="false" 
                class="form-control my-2 autorizaciones" 
                placeholder="Ingrese clave de autorización" 
                name="clave" 
                id="autorizacion{{$factura->id}}">
                <span class="text-danger"></span><br>
                
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Si, proceder a eliminar.</button>
            </form>
        </div>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->



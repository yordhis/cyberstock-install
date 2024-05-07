
        
<!-- Vertically centered Modal -->
<button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modalImprimir">
    <i class="bi bi-printer-fill fs-5"> Imprimir </i> 
</button>
    


<div class="modal fade" id="modalImprimir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Asignar Observación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.exportar.inventario') }}" method="post">
            @csrf
            @method('post')
            <div class="modal-body">
               
                    
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary ">Imprimir inventario</button>
            </div>
        </form>
    </div>
    </div>

  

    
</div><!-- End Vertically centered Modal-->






        
<!-- Vertically centered Modal -->
<a type="button" class="text-white" data-bs-toggle="modal" data-bs-target="#modalPrint">
    <i class="bi bi-printer-fill fs-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Imprimir listas de clientes"></i>
 
</a>
    
<div class="modal fade" id="modalPrint" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Imprimir archivos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="d-flex">
                <a href="{{ route('admin.clientes.export.pdf')}}"
                class="btn btn-danger w-50 m-2"
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                data-bs-title="Imprimir Lista de clientes en formato PDF"
                > 
                    <i class="bi bi-filetype-pdf fs-2"></i>
                    
                </a>
                <a href="{{ route('admin.clientes.export.excel') }}"
                class="btn btn-primary w-50 m-2"
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                data-bs-title="Imprimir Lista de clientes en formato EXCEL"
                > 
                    <i class="bi bi-file-earmark-excel fs-2"></i>
                     
                </a>
            </div>
        </div>
       
    </div>
    </div>
</div><!-- End Vertically centered Modal-->



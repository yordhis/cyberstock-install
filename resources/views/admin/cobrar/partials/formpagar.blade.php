<!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        COBRAR
    </button>
  
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">PROCESAR PAGO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.cuentas.por.cobrar.store') }}" method="POST" class="form-floating" >
                @csrf
                @method('POST')
                <input type="hidden" name="codigo" value="{{ $factura->codigo }}">

                <div class="form-floating">
                    <textarea class="form-control" name="observacion" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 200px">
QUIEN PAGA: YORDHIS
MÉTODO DE PAGO: EFECTIVO
MONTO: 6.96
REFERENCIA:  
TELEFONO: 
OBSERVACIÓN: PAGO  
FECHA: 12-08-2023
                    </textarea>
                    <label for="floatingTextarea2">INGRESE INFORMACIÓN DEL PAGO</label>
                </div>
                <button type="submit" class="btn btn-primary mt-2">REGISTRAR PAGO</button>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>
        </div>
      </div>
    </div>
  </div>
  
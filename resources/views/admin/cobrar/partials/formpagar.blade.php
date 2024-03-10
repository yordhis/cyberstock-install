<!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $factura->id }}">
      <i class="bi bi-paypal fs-4"></i>
    </button>
  
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop{{ $factura->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">FORMULARIO DE PAGO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
            <form action="{{ route('admin.cuentas.por.cobrar.store') }}" method="POST" class="form-floating" >
                @csrf
                @method('POST')
                <input type="hidden" name="codigo_factura" value="{{ $factura->codigo_factura }}">
                <input type="hidden" name="tipo_factura" value="{{ $factura->tipo }}">
                <div class="form-floating mb-3">
                  <h3>Deudor: <span class="text-danger">{{ $factura->cliente[0]->nombre }}</span></h3>
                  <h4>Factura a pagar: <span class="text-danger">{{ $factura->codigo_factura }} </span></h4>
                  <h4>Monto acreditado: <span class="text-danger">{{ number_format($factura->total,2,',','.') }} $</span></h4>
                  <h4>Total abonado: <span class="text-success">{{ number_format($factura->total_abono,2,',','.')}} $</span></h4>
                  <h4>Pendiente por pagar: <span class="text-danger">{{ number_format($factura->total - $factura->total_abono,2,',','.') }} $</span></h4>
                  <p></p>
                </div>
              
                <div class="form-floating mb-3">
                  <select class="form-select" name="metodo" id="floatingSelect" aria-label="Floating label select example" required>
                    <option selected>Seleccione método de pago</option>
                    <option value="BIO PAGO">BIO PAGO</option>
                    <option value="EFECTIVO">EFECTIVO</option>
                    <option value="DIVISAS">DIVISAS</option>
                    <option value="PAGO MOVIL">PAGO MOVIL</option>
                    <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                    <option value="TD">TD | PUNTO</option>
                    <option value="TC">TC | PUNTO</option>
                  </select>
                  <label for="floatingSelect">Método de pago</label>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text">
                    <i class="bi bi-cash-coin fs-3"></i>
                  </span>
                  <div class="form-floating">
                    <input type="number" step="any" name="monto" class="form-control" id="floatingInputGroup1" placeholder="Ingrese monto del pago" required>
                    <label for="floatingInputGroup1">Monto</label>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <span class="input-group-text">
                    <i class="bi bi-calendar-event"></i>
                  </span>
                  <div class="form-floating">
                    <input type="date" name="fecha" class="form-control" id="floatingInputGroup1" placeholder="Ingrese fecha" required>
                    <label for="floatingInputGroup1">Fecha de pago</label>
                  </div>
                </div>
               
                  <button type="submit" class="btn btn-primary mt-2" {{ $factura->concepto == "VENTA" ? "disabled" : "" }}>PROCESAR PAGO</button>
              </form>

        </div>
        <div class="modal-footer d-flex justify-content-between ">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>
        </div>
      </div>
    </div>
  </div>
  
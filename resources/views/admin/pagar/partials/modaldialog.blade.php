 <!-- Modal Dialog Scrollable -->
 {{-- <a  class="text-white" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$factura->id}}">
</a> --}}
<button type="button" class="btn btn-info mx-1" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$factura->id}}">
   <i class="bi bi-card-checklist fs-4"></i>

</button>

  <div class="modal fade" id="modalDialogScrollable{{$factura->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Historial de pagos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    {{-- <img src="{{ $producto->imagen }}" alt="Profile" class="rounded-circle"> --}}
                    <h2> Lista de pagos </h2>
                    {{-- <h3><b>Código de barra: </b>{{ $producto->codigo }} </h3> --}}

                    <div class="container-fluid">
                      <div class="row">
                        <hr>
                        <div class="col-md-12">
                          <h3>Cliente: {{ $factura->proveedor[0]->empresa}}</h3>
                        </div>

                        <table class="table">
                          <thead>
                            <tr>
                              <th>Fecha</th>
                              <th>Método</th>
                              <th>Monto</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($factura->abonos as $abono)
                              <tr>
                                <td>{{ $abono->fecha }}</td>
                                <td>{{ $abono->metodo }}</td>
                                <td>{{ number_format($abono->monto,2,',','.') }}$</td>
                                <td>
                                  <form action="{{ route('admin.cuentas.por.pagar.destroy', $abono->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-outline-danger">
                                      <i class="bi bi-trash3-fill"></i>
                                    </button>

                                  </form>
                                </td>
                              </tr>
                            @endforeach

                          </tbody>
                          <tfoot>
                            <tr>
                              <td><b class="text-danger">Crédito:</b> {{ number_format($factura->total,2,',','.') }}$</td>
                              
                              <td class="text-end"><b>Total abono</b></td>
                              <td class="text-success">{{ number_format($factura->total_abono,2,',','.') }}$</td>
                            </tr>
                            <tr>
                              
                              <td colspan="2" class="text-end"><b>Pendiente</b></td>
                              <td class="text-danger">{{ number_format($factura->total - $factura->total_abono,2,',','.') }}$</td>
                            </tr>
                          </tfoot>
                        </table>
                       
                      </div>
                    </div>
                  </div>
                </div>
      
              </div>
            </div>
          </section>
          
          
            
          


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div><!-- End Modal Dialog Scrollable-->
@extends('layouts.pos')

@section('title', 'POS')


@section('content')


   
    <div class="container-fluid">
       
        <div class="row position-relative">
            
            <div class="col-sm-2 col-xs-12">

                {{-- Tarjeta del cliente --}}
                <div class="card" style="height: auto; width: 100%;" id="tarjetaCliente"></div>
                {{-- CIERRE Tarjeta del cliente --}}

                {{-- Tarjeta del vendedor --}}
                @include('partials.tarjetavendedor')
                {{-- CIERRE Tarjeta del vendedor --}}
                

            </div> {{-- Cierre cliente --}}

            {{-- Filtro de productos --}}
            <div class="col-sm-4 col-xs-12">
                <div class="card" style=" height: 45rem; width: 100%;">
                    {{-- Input para filtrar --}}
                    <div class="card-header" style="height: 8rem;">
                        <h5 class="card-title text-danger">
                            <i class="bi bi-shop"></i>
                            Buscar producto
                        </h5>
                         <div class="input-group mb-3">
                            <span class="input-group-text bg-primary" id="basic-addon1">
                                <i class="bi bi-box text-white"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Ingrese código o descripción" id="buscarProducto">
                        </div>
                    </div>

                    {{-- lista de productos --}}
                    <div class="card-body table-responsive" style="height: 20rem; overflow: auto;" >
                       <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Disp.</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBuscarProducto">
                                
                               
                            </tbody>
                       </table>
                    </div>
                    <div class="card-footer" id="totalProductosFiltrados">
                       
                    </div>
                </div>            
            </div> {{-- CIERRE Filtro de productos --}}

            {{-- Factura --}}
            <div class="col-sm-5 col-xs-12">
                <div class="card" style=" height: auto; width: 100%;">
                    {{-- alertas o mensaje de respuestas --}}
                    <div class="position-relative">
                        <div class="position-absolute top-0 end-0" id="alertas">
                        </div>
                    </div>

                    <div class="card-header" style="height: 4rem;">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title text-danger" id="codigoFactura">
                                {{-- aqui se carga el numero de la factura --}}
                            </h5>
                            <h5 class="card-title text-danger" id="fechaFactura">
                                Fecha: {{ date('d-m-Y h:ia') }}
                            </h5>

                        </div>
                    </div>
                    <div class="card-body" style="height: 25rem; overflow: auto;">
                       <table class="table ">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Cant.</th>
                                    <th>C/U</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="listaDeProductosEnFactura">
                            
                                
                            </tbody>
                       </table>
                    </div>
                    <div class="card-footer">
                        <div class="row" id="componenteFactura">
                        </div>
                    </div>
                </div>
               
            </div> {{-- CIERRE Factura --}}

            {{-- MAs botones --}}
            <div class="col-sm-1 col-xs-12 card">
                <div class="row">
                    {{--  Boton de salir --}}
                    <div class="col-sm-12 acciones-factura">
                        <button class="btn btn-danger w-100 my-2" id="salirDelPos">
                            <i class='bx bx-reply-all fs-3'></i> 
                            <p> Salir</p>
                        </button>
                    </div>
    
                    {{--  Boton de DEVOLUCIÓN --}}
                    <div class="col-sm-12">
                        <button class="btn btn-primary w-100 my-2" data-bs-toggle="modal" data-bs-target="#disablebackdrop">
                            <i class='bx bx-transfer-alt fs-3'></i>
                            <p> Devolución </p>
                        </button>

                        <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Realizar devolución o reembolso</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="inputCodigoDeLaFactura" placeholder="Ingrese código de la factura">
                                        <span class="text-danger"></span>
                                        <label for="floatingInput">Código de factura</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                  <button type="button" class="btn btn-primary acciones-factura" id="cargarFacturaDevolucion">Cargar Factura</button>
                                </div>
                              </div>
                            </div>
                        </div><!-- End Disabled Backdrop Modal-->
                    </div>
        
                    {{--  Boton de COLOCAR EN ESPERA LA FACTURA --}}
                    <div class="col-sm-12  acciones-factura">
                        <button class="btn btn-primary  w-100 my-2" 
                            id="facturaEnEspera" >
                            <i class='bx bxs-hourglass-top fs-3'></i> 
                            <p> Guardar </p> 
                        </button>
                    </div>
                    {{--  Boton de CARGAR FACTURA EN ESPERA PARA FACTURAR --}}
                    <div class="col-sm-12  acciones-factura">
                        <button class="btn btn-primary  w-100 my-2" 
                            id="cargarFactura" >
                            <i class='bx bxs-hourglass-top fs-3 text-white'></i> 
                            <p id="mensajeDeEspera"> En espera </p> 
                        </button>
                    </div>

                    {{--  Boton de ELIMINAR FACTURA DE ESPERA --}}
                    <div class="col-sm-12  acciones-factura">
                        <button class="btn btn-primary  w-100 my-2" 
                            id="limpiarBorrador" >
                            <i class='bx bxs-hourglass-top fs-3 text-white'></i> 
                            <p> Limpiar factura en espera</p> 
                        </button>
                    </div>
                    <div class="col-sm-12  acciones-factura">
                        <button class="btn btn-warning  w-100 my-2" 
                            id="finalizarFacturacion" >
                            <i class="bi bi-arrow-clockwise fs-3 text-white"></i>
                            <p> Resetear </p> 
                        </button>
                    </div>
                </div>
            </div>


            {{-- Metodos de pago --}}
            <div class="col-sm-8" id="elementoMetodoDePagoModal"></div>
        </div>
    </div>
    
    <!-- SCRIPT DE APP -->
  
    <script src="{{ asset('/js/main.js') }}" defer></script>
    <script src="{{ asset('/js/partials/customModal.js') }}" defer></script>
    <script src="{{ asset('/js/inventarios/inventarioController.js') }}" defer></script>
    <script src="{{ asset('/js/facturas/facturaController.js') }}" defer></script>
    <script src="{{ asset('/js/clientes/clienteController.js') }}" defer></script>
    <script src="{{ asset('/js/utilidad/getIva.js') }}" defer></script>
    <script src="{{ asset('/js/pos/index.js') }}" defer></script>
    <!-- CIERRE ! SCRIPT DE APP -->


@endsection

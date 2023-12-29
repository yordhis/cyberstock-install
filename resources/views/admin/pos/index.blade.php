@extends('layouts.pos')

@section('title', 'POS')


@section('content')


   
    <div class="container-fluid">
       
        <div class="row position-relative">
            <div class="col-sm-2 col-xs-12">

                {{--  Boton de salir --}}
                <div class="card"  style=" height: 4.5rem; width: 100%;">
                    <button class="btn btn-danger fs-3 h-100 acciones-factura" id="salirDelPos">
                        <i class='bx bx-reply-all'></i> 
                        Salir
                    </button>
                </div>

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
            <div class="col-sm-6 col-xs-12">
                <div class="card" style=" height: 45rem; width: 100%;">
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
                    <div class="card-body table-responsive" style="height: 25rem; overflow: auto;">
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

            {{-- Metodos de pago --}}
            <div class="col-sm-8" id="elementoMetodoDePagoModal">
               
  
            </div>
        </div>
    </div>
    
    <!-- SCRIPT DE APP -->
  
    <script src="{{ asset('/js/main.js') }}" defer></script>
    <script src="{{ asset('/js/partials/customModal.js') }}" defer></script>
    <script src="{{ asset('/js/inventarios/inventarioController.js') }}" defer></script>
    <script src="{{ asset('/js/facturas/facturaController.js') }}" defer></script>
    <script src="{{ asset('/js/clientes/clienteController.js') }}" defer></script>
    <script src="{{ asset('/js/pos/index.js') }}" defer></script>
    <!-- CIERRE ! SCRIPT DE APP -->


@endsection

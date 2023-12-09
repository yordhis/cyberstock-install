@extends('layouts.pos')

@section('title', 'POS Entradas')


@section('content')
    @isset($respuesta)
        @include('partials.alert')
    @endisset
    <div id="alert">
    </div>

 
    <div class="container-fluid ">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="productos" class="btn btn-primary m-2 p-2" target="_self">Crear Producto</a>
        </div>

        <div class="row position-relative">
            {{-- Proveedor --}}
            <div class="col-sm-2 col-xs-12">

                {{--  Boton de salir --}}
                <div class="card"  style=" height: 4.5rem; width: 100%;">
                    <button class="btn btn-danger fs-3 h-100 acciones-factura" id="salirDelPos">
                        <i class='bx bx-reply-all'></i> 
                        Salir
                    </button>
                </div>

                {{-- Tarjeta del proveedor --}}
                <div class="card" style="height: 25.5rem; width: 100%;" id="tarjetaCliente"></div>

                {{-- Tarjeta del usuario --}}
                <div class="card" style="height: 11rem; width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Usuario ID: {{ Auth::user()->id ?? 'User no definido' }}</h5>
                
                        <p class="card-text" style="">
                            <b>Usuario:</b> {{ Auth::user()->nombre ?? 'User no definido' }} <br>
                            <b>Fecha y Hora:</b> {{ date('d/m/Y - h:m:sa') }}
                        </p>
                    </div>
                </div>
                

            </div> {{-- Cierre proveedor --}}

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
                                    <th>Marca</th>
                                    <th>Categoria</th>
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
                    <div class="card-header" style="height: 4rem;">
                        <div class="d-flex justify-content-between p-0 m-0">
                            <label for="" class="text-danger">
                                <i class="bi bi-square-half"></i>
                                Ingrese número de factura Proveedor
                            </label>
                            <input type="number" class=" text-danger" id="codigoFacturaProveedor" name="Codigo Factura Proveedor" style="width: 50%; height: 50%;"/>
                            <span class="text-danger"></span>
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

            <div class="col-sm-8" id="elementoMetodoDePagoModal">
              
  
            </div>
        </div>
    </div>

    <!-- APP JS -->
    <script src="{{ asset('/js/main.js') }}" defer></script>
    
    <script src="{{ asset('/js/productosEntradas/index.js') }}" defer></script>
    <script src="{{ asset('/js/proveedores/proveedorController.js') }}" defer></script>
    <script src="{{ asset('/js/productos/productoController.js') }}" defer></script>
    <script src="{{ asset('/js/facturas/facturaController.js') }}" defer></script>
    <script src="{{ asset('/js/partials/customModal.js') }}" defer></script>
    <script src="{{ asset('/js/inventarios/inventarioController.js') }}" defer></script>
    <!-- CIERRE APP JS -->
    
    <!-- SCRIPT DE ESTILOS -->
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}" defer></script>
    <!-- CIERRE SCRIPT DE ESTILOS -->

@endsection

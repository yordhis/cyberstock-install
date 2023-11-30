@extends('layouts.pos')

@section('title', 'POS')


@section('content')
    @isset($respuesta)
        @include('partials.alert')
    @endisset
    <div id="alert">
    </div>

   
    <div class="container-fluid ">
       
        <div class="row position-relative">
            <div class="col-12">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-1">
                    <a href="pos/facturas" class="btn btn-primary m-2"  target="_self">
                        <i class="bi bi-paypal"></i>
                        Facturas
                    </a>
                    <a href="pos/clientes" class="btn btn-primary m-2"  target="_self">
                        <i  class="bi bi-person"></i>
                        Clientes
                    </a>
                </div>
            </div>
            {{-- Cliente --}}
            <div class="col-sm-2 col-xs-12">

                {{--  Boton de salir --}}
                <div class="card"  style=" height: 4.5rem; width: 100%;">
                    <button class="btn btn-danger fs-3 h-100 acciones-factura" id="salirDelPos">
                        <i class='bx bx-reply-all'></i> 
                        Salir
                    </button>
                </div>

                {{-- Tarjeta del cliente --}}
                <div class="card" style="height: 25.5rem; width: 100%;" id="tarjetaCliente">       
                </div>

                {{-- Tarjeta del vendedor --}}
                <div class="card" style="height: 11rem; width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Vendedor ID: {{ Auth::user()->id }}</h5>
                
                        <p class="card-text" style="">
                            <b>Usuario:</b> {{ Auth::user()->nombre }} <br>
                            <b>Fecha y Hora:</b> {{ date('d/m/Y - h:m:sa') }}
                        </p>
                    </div>
                </div>
                

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




@endsection

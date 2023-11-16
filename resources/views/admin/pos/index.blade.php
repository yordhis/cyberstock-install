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

            <div class="col-sm-8" id="elementoMetodoDePagoModal">
                <!-- Button trigger modal -->
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Launch static backdrop modal
                </button> --}}
                
                <!-- Modal -->
                {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Agregar metodos de pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                        </div>
                    </div>
                    </div>
                </div> --}}
  
            </div>
        </div>
    </div>




@endsection

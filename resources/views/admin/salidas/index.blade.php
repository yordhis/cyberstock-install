@extends('layouts.app')

@section('title', 'Procesar Salida')


@section('content')
    @isset($respuesta)
        @include('partials.alert')
    @endisset
    <div id="alert"></div>

    <div class="container-fluid">
        <section class="section register d-flex flex-column align-items-center justify-content-center ">
            <div class="container">
                <div class="row justify-content-center">
                    <!-- Botones de agregar clientes y productos -->
                    <div class="col-12 mb-2">

                        <div class="d-flex flex-row-reverse bd-highlight">

                            <div class="list-group w-25">
                                <label for="">Tasa de Venta </label>
                                <input type="number" name="tasa" id="tasa"
                                    class="form-control d-flex justify-content-end mb-2 factura"
                                    value="{{ $tasa }}">
                            </div>

                            @include('admin.salidas.partials.modalEliminarFactura')

                            @include('admin.salidas.partials.modalCrearCliente')
                            {{-- <button class="btn btn-primary h-25 mt-4 mb-2 me-2">Agregar Producto</button> --}}
                        </div>
                    </div> <!-- Cierre Botones de agregar clientes y productos -->

                    <!-- Tarjeta contenedora del POS -->
                    <div class=" col-sm-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="card ">
                            <div class="card-body">
                                <!-- Formulario de agregar productos al caarrito -->
                                <form action="storeSalida" method="post" target="_self" class="g-3 needs-validation" novalidate>
                                    @csrf
                                    @method('post')
                                    <div class="row g-3">
                                        <!-- Input de Tipo de Transacción -->
                                        <div class="col-sm-6 col-xs-12 mt-4">
                                            <label for="yourUsername" class="form-label">Transacción</label>

                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bi bi-server"></i>
                                                </span>
                                                <input type="text" name="tipoTransaccion"
                                                    class="form-control fs-5 text-danger input-entrada factura"
                                                    id="tipoTransaccion" 
                                                    value="SALIDA" readonly
                                                    required>
                                                <div class="invalid-feedback">Por favor, ingrese tipo de transaccion! </div>
                                            </div>
                                        </div><!-- Cierre Input de Tipo de Transacción -->

                                        <!-- Input de cod   igo de transacción -->
                                        <div class="col-sm-6 col-xs-12 mt-4">
                                            <label for="yourUsername" class="form-label">Número de transacción
                                                <span class=" text-primary">(Es Automático)</span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bi bi-upc-scan"></i>
                                                </span>
                                                <input type="number" name="codigo"
                                                    class="form-control fs-5 text-danger input-entrada factura"
                                                    id="codigo" placeholder="Ingrese número de transacción" 
                                                    value="{{ $codigo }}" readonly
                                                    required>
                                                <div class="invalid-feedback">Por favor, ingrese código de transaccion! </div>
                                            </div>
                                        </div><!-- Cierre Input de codigo de factura -->

                                        <!-- Input de codigo de factura proveedor -->
                                        <div class="col-sm-6 col-xs-12 mt-4">
                                            <label for="yourUsername" class="form-label">Número de factura 
                                                <span class=" text-primary">(Es Automático)</span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bi bi-upc-scan"></i>
                                                </span>
                                                <input type="text" name="codigo_factura"  
                                                    value="{{ $codigoFactura ?? '' }}"
                                                    class="form-control fs-5 text-danger input-entrada factura"
                                                    id="codigo_factura" placeholder="Ingrese código de factura" readonly required>
                                                <div class="invalid-feedback">Por favor, ingrese código de factura! </div>
                                            </div>
                                        </div><!-- Cierre Input de codigo de factura proveedor -->

                                        <!-- Input de codigo de factura proveedor -->
                                        <div class="col-sm-6 col-xs-12 mt-4">
                                            <label for="yourUsername" class="form-label">Fecha de la factura
                                                <span class=" text-primary">(Es Obligatorio)</span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bi bi-calendar"></i>
                                                </span>
                                                <input type="date" name="fecha"
                                                @if(count($carritos))
                                                    value="{{ $carritos[0]->fecha ?? ''}}"
                                                @endif
                                                    class="form-control fs-5 text-danger input-entrada factura"
                                                    id="fecha_factura_proveedor" placeholder="Ingrese número de factura" required>
                                                <div class="invalid-feedback">Por favor, ingrese fecha de factura! </div>
                                            </div>
                                        </div><!-- Cierre Input de codigo de factura proveedor -->

                                       
                                        
                                         <!-- Input de CONCEPTO DE MOVIMIENTO -->
                                         <div class="col-sm-6 col-xs-12 mt-4">
                                            <label for="yourUsername" class="form-label">Espesífique Concepto
                                                <span class=" text-danger">(Es Obligatorio)</span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bi bi-option"></i>
                                                </span>

                                                @if(isset($_GET['conceptoDeMovimiento']))
                                                    <input type="text" name="conceptoDeMovimiento"
                                                    class="form-control fs-5 text-danger input-entrada factura"
                                                    id="conceptoDeMovimiento" placeholder="Ingrese concepto de movimiento" 
                                                    value="{{ $_GET['conceptoDeMovimiento'] }}" readonly
                                                    required>
                                                @else
                                                    <select id="conceptoDeMovimiento" name="conceptoDeMovimiento"  class="form-select factura" required>
                                                        <option  selected disabled value="">Concepto de movimiento</option>
                                                        <option value="CREDITO"> CREDITO </option>
                                                        <option value="VENTA"> VENTA </option>
                                                        <option value="CONSUMO"> CONSUMO </option>
                                                    </select>
                                                @endif
                                               
                                             
                                                <div class="invalid-feedback">Por favor, ingrese concepto de movimiento de inventario. </div>
                                            </div>
                                        </div><!-- Cierre Input de CONCEPTO DE MOVIMIENTO -->

                                        <!-- Input de IDENTIFICACION -->
                                        <div class="col-sm-6 col-xs-12 mt-4">
                                            <label for="yourUsername" class="form-label">RIF o Documento de identidad
                                                <span class=" text-primary">(Es obligatorio*)</span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bi bi-person-fill-up"></i>
                                                </span>

                                                @if (isset($carritos[0]))
                                                    <input type="number" name="identificacion"
                                                        class="form-control fs-5 input-entrada factura rif"
                                                        id="rif"
                                                        value="{{ $_GET['identificacion'] ?? $carritos[0]->identificacion}}"
                                                        {{ $carritos[0]->identificacion ? 'readonly' : '' }} required>
                                                @else
                                                    <input type="number" name="identificacion"
                                                        class="form-control fs-5 input-entrada factura rif"
                                                        id="rif" value="{{ $_GET['identificacion'] ??  ''}}" placeholder="Ingrese RIF o Documento de identidad " required>
                                                @endif
                                                <div class="invalid-feedback">Por favor, ingrese Rif o Documento de identidad del proveedor o vendedor! </div>
                                            </div>
                                            <span id="razon_social" class="factura card p-2">@isset($carritos[0]['cliente'])<h4>Razón Social:{{ $carritos[0]['cliente'][0]->nombre ?? '' }}</h4>@endisset</span>
                                        </div><!-- Cierre Input de IDENTIFICACION -->

                                       




                                        <!-- Span de informacion del cliente -->
                                        {{-- <div class="col-sm-6 col-xs-12 mt-2">
                                           
                                        </div> --}}

                                        <!-- Formulario tabla de agregar productos al carrito -->
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body table-responsive">
                                                    <!-- Tabla de agregar productos al carrito -->
                                                    <table class="table  ">
                                                        <thead>
                                                            <tr>

                                                                <th scope="col">Código</th>
                                                                <th scope="col">Descripción</th>
                                                                <th scope="col">PVP REF</th>
                                                                <th scope="col">PVP BS</th>
                                                                <th scope="col">Cantidad</th>
                                                                <th scope="col">Subtotal REF</th>
                                                                <th scope="col">Btn</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="formCarrito">
                                                            <tr>

                                                                <td>
                                                                    <input type="text" name="codigo_producto"
                                                                        id="codigo_producto"
                                                                        value="{{ $_GET['codigo_producto'] ?? ''}}"
                                                                        class="form-control input-entrada"
                                                                        placeholder="Código" required>
                                                                </td>
                                                                <td style="width: 350px">
                                                                    <input type="text" name="descripcion"
                                                                        
                                                                        id="descripcion" class="form-control input-entrada"
                                                                        placeholder="Descripción" readonly required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="any" name="costo"
                                                                        
                                                                        id="costo_unitario"
                                                                        class="form-control input-entrada"
                                                                        placeholder="Costo" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="any" name="costo_unitario_bs"
                                                                        
                                                                        id="costo_unitario_bs"
                                                                        class="form-control input-entrada"
                                                                        placeholder="Costo en Bolivares" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="any" name="cantidad"
                                                                        
                                                                        id="cantidad" class="form-control input-entrada"
                                                                        placeholder="Cantidad" required>
                                                                </td>
                                                                <td class="tdSubtotales">
                                                                    <input type="number" step="any" name="subtotal"
                                                                        
                                                                        id="subtotal" class="form-control input-entrada"
                                                                        placeholder="Sub-Total" required>
                                                                </td>
                                                                <td>
                                                                    <button id="agregarAlCarro" type="submit">
                                                                        <i class='bx bx-plus-medical fs-3'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="7" id="mensajeInventario"></td>
                                                            </tr>
                                                             {{--  Configuración de precios del producto --}}
                                                           
                                                            <tr id="trPvpInputsRadios">
                                                           
                                                            </tr>
                                                           

                                                            @isset($_GET['mensajeInventario'])
                                                                <tr>
                                                                    <td colspan="6" class="text-danger">
                                                                        {{ $_GET['mensajeInventario'] ?? ''}}
                                                                    </td>
                                                                </tr>
                                                            @endisset

                                                        </tbody>
                                                    </table>

                                                    <!-- Cierre Tabla de agregar productos al carrito -->

                                                </div>
                                            </div>
                                </form><!-- Cierre Formulario de agregar productos al caarrito -->


                            </div>

                            {{-- Carrito o factura --}}
                            <div class="col-12">

                                <div class="card">
                                    <div class="card-body table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>

                                                    <th scope="col">Código</th>
                                                    <th scope="col">Descripción</th>
                                                    <th scope="col">Cost. Unitario</th>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col">Subtotal</th>
                                                    <th scope="col">Btn</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Recorremos el carrito --}}
                                                @if (!count($carritos))
                                                    <tr class="text-center">
                                                        <td colspan="6">No hay Productos en el carrito</td>
                                                    </tr>
                                                @endif
                                                @foreach ($carritos as $carrito)
                                                    <tr>
                                                        <td>{{ $carrito->codigo_producto }}</td>
                                                        <td>{{ $carrito->descripcion }}</td>
                                                        <td>{{ $carrito->costoBs }}</td>
                                                        <td>{{ $carrito->cantidad }}</td>
                                                        <td class="tdSubtotales">
                                                            {{ $carrito->subtotalBs }}
                                                            <span class="subtotalProductos" style="display: none;">
                                                                {{ $carrito->subtotal }}
                                                            </span>

                                                        </td>

                                                        <td>
                                                            @include('admin.pos.partials.modalEliminar')
                                                        </td>
                                                    </tr>
                                                @endforeach  {{-- Cierre Recorremos el carrito --}}


                                                {{-- DATOS FACTURA --}}
                                                <tr>
                                                    <td colspan="3" class="bg-primary"></td>
                                                    <td>Descuento %</td>
                                                    <td class="text-center">
                                                        <input type="number" name="descuento" id="descuento"
                                                            value="0" class="form-control factura" step="any">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="bg-primary">
                                                    <td>Sub-Total</td>
                                                    <td class="text-center">
                                                        <input type="number" name="subtotal_temporal_bs"
                                                            id="subtotal_temporal_bs" readonly
                                                            class="form-control factura" step="any">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="bg-primary">
                                                    <td>Sub-Total Divisas</td>
                                                    <td class="text-center">
                                                        <input type="number" name="subtotal_temporal_usd"
                                                            id="subtotal_temporal_usd" readonly
                                                            class="form-control factura" step="any">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="bg-primary">
                                                    <td>Iva %</td>
                                                    <td class="text-center">
                                                        <input type="number" name="iva" id="iva"
                                                            value="{{ $iva['iva'] }}" class="form-control factura"
                                                            step="any">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="bg-primary">
                                                    <td>Total</td>
                                                    <td class="text-center fs-3">
                                                        <input type="number" name="total" id="total" readonly
                                                            class="form-control factura" step="any">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="bg-primary">
                                                    <td>Total en Divisdas</td>
                                                    <td class="text-center fs-4">
                                                        <input type="number" name="totalDivias" id="totalDivias"
                                                            readonly class="form-control factura" step="any">
                                                    </td>
                                                    <td></td>
                                                </tr>{{--  Cierre de datos de factura --}}
                                                
                                                {{--  Métodos de pagos --}}
                                                <tr>
                                                    <td colspan="3" class="bg-primary">
                                                    <td>Métodos de pago</td>
                                                    <td class="text-center fs-4">
                                                        <div class="metodoAdd row g-3">
                                                            <div class="col-md-6">
                                                                <select class="form-select metodoPago">
                                                                  <option selected>Método de pago</option>
                                                                  <option value="EFECTIVO">EFECTIVO</option>
                                                                  <option value="PAGO MOVIL">PAGO MOVIL</option>
                                                                  <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                                                  <option value="TD">TD | PUNTO</option>
                                                                  <option value="TC">TC | PUNTO</option>
                                                                 
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" id='pagoCliente' step="any" class="form-control metodoPago pagoCliente">
                                                            </div>
    
                                                            <div class="col-md-2 ">
                                                                <a href="#" id="agregarMetodo" type="buttom" class="metodoPago">
                                                                    <i class='bx bx-plus-medical text-primary fs-5' id="agregarMetodo"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    
                                                        <div id="otroMetodoPago"></div>
                                                        
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                {{-- Restante o vuelto --}}
                                                <tr>
                                                    <td colspan="3" class="bg-primary">
                                                    <td>Cambio</td>
                                                    <td class="text-center fs-4">
                                                        <input type="number" name="restante" id="restante"
                                                            readonly class="form-control factura" step="any">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td colspan="6">
                                                        <span class="btn btn-primary w-100" id="btnProcesar">Procesar
                                                            movimiento de inventario</span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            {{-- Cierre Carrito o factura --}}


                        </div>
                    </div> <!-- Cierre Tarjeta contenedora del POS -->

                </div>
            </div>
    </div>

    </section>

@endsection

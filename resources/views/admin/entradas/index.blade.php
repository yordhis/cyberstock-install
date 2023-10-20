@extends('layouts.app')

@section('title', 'Procesar Entrada')


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
                                <label for="">Tasa del dia BCV </label>
                                <input type="number" name="tasa" id="tasa" readonly
                                    class="form-control d-flex justify-content-end mb-2 factura"
                                    value="{{ $tasa }}">
                            </div>

                            @include('admin.entradas.partials.modalEliminarFactura')

                            @include('admin.entradas.partials.modalCrearCliente')
                            {{-- <button class="btn btn-primary h-25 mt-4 mb-2 me-2">Agregar Producto</button> --}}
                        </div>
                    </div> <!-- Cierre Botones de agregar clientes y productos -->

                    <!-- Tarjeta contenedora del POS -->
                    <div class=" col-sm-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="card ">
                            <div class="card-body">
                                <!-- Formulario de agregar productos al caarrito -->
                                <form action="carritos" method="post" target="_self">
                                    @csrf
                                    @method('post')
                                    <div class="row g-3">
                                        <input type="hidden" name="formulario" value="crearEntrada">
                                        <!-- Input de codigo de factura -->
                                        <div class="col-sm-6 col-xs-12 mt-4">
                                            <label for="yourUsername" class="form-label">Número de factura
                                                <span class=" text-primary">(Es Obligatorio)</span>
                                            </label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bi bi-upc-scan"></i>
                                                </span>
                                                <input type="number" name="codigo"
                                                    class="form-control fs-5 text-danger input-entrada factura"
                                                    id="codigo_factura" placeholder="Ingrese número de factura" required>
                                                <div class="invalid-feedback">Por favor, ingrese código de factura! </div>
                                            </div>
                                        </div><!-- Cierre Input de codigo de factura -->

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
                                                        class="form-control fs-5 input-entrada factura identificacion"
                                                        id="cedula"
                                                        value="{{ $_GET['identificacion'] ?? $carritos[0]->identificacion}}"
                                                        {{ $carritos[0]->identificacion ? 'readonly' : '' }} required>
                                                @else
                                                    <input type="number" name="identificacion"
                                                        class="form-control fs-5 input-entrada factura identificacion"
                                                        id="cedula" value="" placeholder="Ingrese RIF o Documento de identidad " required>
                                                @endif
                                                <div class="invalid-feedback">Por favor, ingrese Rif o Documento de identidad del proveedor o vendedor! </div>
                                            </div>
                                            <span id="dataProveedor" class="factura card p-3">@isset($carritos[0]['proveedor'])<h4>{{ "Proveedor: " . $carritos[0]['proveedor']->nombre ?? '' }}</h4>@endisset</span>
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
                                                                <th scope="col">COSTO REF</th>
                                                                <th scope="col">COSTO BS</th>
                                                                <th scope="col">Cantidad</th>
                                                                <th scope="col">Subtotal</th>
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
                                                                <td>
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
                                            <tbody id="carritoEntrada">
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
                                                        <td>
                                                            {{ $carrito->subtotalBs }}
                                                            <span class="subtotalProductos" style="display: none;">
                                                                {{ $carrito->subtotal }}
                                                            </span>

                                                        </td>

                                                        <td>
                                                            @include('admin.pos.partials.modalEliminar')
                                                        </td>
                                                    </tr>
                                                @endforeach


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
                                                </tr>



                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td colspan="6">
                                                        <span class="btn btn-primary w-100" id="procesarVenta">Procesar
                                                            Venta</span>
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
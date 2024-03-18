@extends('layouts.app')

@section('title', 'Panel Principal')

@section('content')
    <section class="section dashboard">
        <div id="alert"></div>
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Total de productos -->
                    <div class="col-sm-4">
                        <div class="card info-card sales-card rounded-3">

                            <div class="card-body">
                                <h5 class="card-title">Productos</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                        <i class="bi bi-box-seam text-primary"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $datosDash['totalProductos'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Activos</span>
                                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- FIN Total de productos -->
                    
                    <!-- Total de clientes -->
                    <div class="col-sm-4">
                        <div class="card info-card sales-card rounded-3">

                            <div class="card-body">
                                <h5 class="card-title">Clientes</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                        <i class="bi bi-person-video3 text-danger"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $datosDash['totalClientes'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Activos</span>
                                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- FIN Total de clientes -->

                    @if ( Auth::user()->rol == 1 || Auth::user()->rol == 2 )
                        <!-- Total de Proveedores -->
                        <div class="col-sm-4">
                            <div class="card info-card sales-card rounded-3">

                                <div class="card-body">
                                    <h5 class="card-title">Proveedores</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                            <i class="bi bi-person-vcard text-info"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $datosDash['totalProveedores']  }}</h6>
                                            <span class="text-muted small pt-2 ps-1">Activos</span>
                                            {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- FIN Total de Proveedores -->

                        <!-- Total de Cuentas por cobrar -->
                        <div class="col-sm-4">
                            <div class="card info-card sales-card rounded-3">

                                <div class="card-body">
                                    <h5 class="card-title">Cuentas Por cobrar</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                            <i class="bi bi-palette2 text-warning"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{  $datosDash['totalFacturasPorCobrar']  }}</h6>
                                            <span class="small pt-2 ps-1">
                                                <a href="{{  route('admin.cuentas.por.cobrar.index') }}" class="text-primary" >
                                                    Ver lista
                                                </a>    
                                            </span>
                                            {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- FIN Total de Cuentas por cobrar -->

                        <!-- Cuentas por pagar -->
                        <div class="col-sm-4">
                            <div class="card info-card sales-card rounded-3">

                                <div class="card-body">
                                    <h5 class="card-title">Cuentas por pagar</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                            <i class="bi bi-cash text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $datosDash['totalFacturasPorPagar'] }}</h6>
                                            <span class="small pt-2 ps-1">
                                                <a href="{{  route('admin.cuentas.por.pagar.index') }}" class="text-primary" >
                                                    Ver lista
                                                </a>    
                                            </span>
                                            {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- Fin Cuentas por pagar -->
                    @endif


                    <!-- POS -->
                    <div class="col-sm-4">
                        <div class="card info-card sales-card bg-primary rounded-3">
                            <a href="/pos" >
                                <div class="card-body">
                                    <h5 class="card-title"></span></h5>
    
                                    <div class="d-flex align-items-center">
                                        <div class="ps-3">
                                            <h2 class="text-white me-2">POS</h6>
                                        </div>
    
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                            <i class="bi bi-paypal text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div><!-- End POS -->
                   
                </div>
            </div>
        </div>
    </section>
    
@endsection
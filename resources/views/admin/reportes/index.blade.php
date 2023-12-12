@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-12">
                <h2>REPORTES</h2>
            </div>
           

            <div class="col-sm-12  ">

                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Configure reporte</h5>
        
                      <!-- Floating Labels Form -->
                      <form id="storeReportes" action="storeReportes" method="POST" target="_self"  class="row g-3">
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="date" name="inicio" class="form-control" id="floatingName" placeholder="Your Name">
                            <span class="text-danger"></span>
                            <label for="floatingName">Fecha de inicio (Desde)</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="date" name="fin" class="form-control" id="floatingEmail" placeholder="Your Email">
                            <span class="text-danger"></span>
                            <label for="floatingEmail">Fecha de cierre (Hasta)</label>
                          </div>
                        </div>
                        
                        {{-- <div class="col-md-4">
                          <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" aria-label="State">
                              <option selected>New York</option>
                              <option value="1">Oregon</option>
                              <option value="2">DC</option>
                            </select>
                            <label for="floatingSelect">State</label>
                          </div>
                        </div> --}}
                        
                        <div class="text-center">
                          <button type="submit" class="btn btn-primary">GENERAR</button>
                          <button type="reset" class="btn btn-secondary">RESETEAR FORMULARIO</button>
                        </div>
                      </form><!-- End floating Labels Form -->
                      <span class="text-danger"></span>
                    </div>
                </div>

            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Reporte rapido</h5>
        
                      <button type="button" class="btn btn-primary reporte-rapido" id="reporteDelDia"><i class="bi bi-star me-1"></i> REPORTE DE VENTA DEL DIA</button>
                      {{-- <button type="button" class="btn btn-warning reporte-rapido" id="reporteSemanal"><i class="bi bi-receipt me-1"></i> REPORTE DE VENTA DE LA SEMANA </button> --}}
                      <button type="button" class="btn btn-primary reporte-rapido" id="reporteDelMes"><i class="bi bi-receipt me-1"></i> REPORTE DE VENTA DEL MES </button>
   
                    
                    </div>
                </div>
            </div>



        </div>
    </section>

@endsection

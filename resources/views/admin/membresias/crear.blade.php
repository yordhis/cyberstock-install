@extends('layouts.app')

@section('title', 'Crear Licencia')


@section('content')
    <div class="container">
        @include('partials.alertSession')
        <div id="alert"></div>

        <section class="section register d-flex flex-column align-items-center justify-content-center ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class=" col-sm-8 d-flex flex-column align-items-center justify-content-center">


                        <div class="card ">

                            <div class="card-body">

                                <div class=" pb-2">
                                    <h5 class="card-title text-center pb-0 fs-2">Crear membresia</h5>
                                    <p class="text-center text-danger small">Rellene todos los campos</p>
                                </div>

                                <form action="{{ route('admin.membresias.store') }}" method="post" 
                                enctype="multipart/form-data"
                                    class="row g-3 needs-validation shadow-lg" novalidate>
                                    @csrf
                                    @method('post')

                                    <div class="col-12">
                                        <label for="paquete" class="form-label">nombre del paquete</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend"></span>
                                            <input type="text" name="paquete" class="form-control" id="paquete"
                                                placeholder="Ingrese nombre del paquete"
                                                value="{{ old('paquete') ?? '' }}" required>
                                            <div class="invalid-feedback">Ingrese el nombre del paquete contratado! </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="empresa" class="form-label">nombre de la empresa</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend"></span>
                                            <input type="text" name="empresa" class="form-control" id="empresa"
                                                placeholder="Ingrese nombre del empresa"
                                                value="{{ old('empresa') ?? '' }}" required>
                                            <div class="invalid-feedback">Ingrese el nombre de la empresa. </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="rif" class="form-label">Rif o número de documento</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend"></span>
                                            <input type="text" name="rif" class="form-control" id="rif"
                                                placeholder="Ingrese rif o número de documento"
                                                value="{{ old('rif') ?? '' }}" required>
                                            <div class="invalid-feedback">Ingrese el nombre de la rif. </div>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <label for="fecha_inicio" class="form-label">Fecha de inicio de contrato</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend"></span>
                                            <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio"
                                          
                                                value="{{ old('fecha_inicio') ?? '' }}" required>
                                            <div class="invalid-feedback">Por favor ingrese fecha de inicio del contrato! </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="fecha_fin" class="form-label">Fecha de vencimiento del contrato</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend"></span>
                                            <input type="date" name="fecha_fin" class="form-control" id="fecha_fin"
                                          
                                                value="{{ old('fecha_fin') ?? '' }}" required>
                                            <div class="invalid-feedback">Por favor ingrese fecha de vencimiento del contrato! </div>
                                        </div>
                                    </div>

                                    

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Crear membresia</button>
                                    </div>

                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>
    @endsection

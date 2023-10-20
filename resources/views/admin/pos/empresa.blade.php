@extends('layouts.app')

@section('title', 'Datos de Empresa')


@section('content')
    <div class="container">


        @isset($respuesta)
            @include('partials.alert')
        @endisset
        <div id="alert"></div>

        <section class="section register  ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class=" col-sm-12 ">


                        <div class="card ">

                            <div class="card-body">

                                <div class=" pb-2">
                                    <h5 class="card-title text-center pb-0 fs-2">Actualizar Datos de Empresa</h5>
                                    <p class="text-center text-danger small">Rellene todos los campos</p>
                                </div>

                                <form action="/pos/{{ $po->id }}" method="post" target="_self"
                                    enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                    @csrf
                                    @method('put')
                                    <div class="col-sm-6 col-xs-12">
                                        <label for="yourUsername" class="form-label">Nombre de la empresa</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                <i class="bx bx-money-withdraw"></i>
                                            </span>
                                            <input type="text" name="empresa" class="form-control" id="yourUsername"
                                                placeholder="Ingrese nombre de empresa "
                                                value="{{ $po->empresa ?? $request->empresa }}" required>
                                            <div class="invalid-feedback">Por favor, ingrese nombre de empresa </div>
                                            @error('empresa')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <label for="yourUsername" class="form-label">Rif</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                <i class="bx bx-money-withdraw"></i>
                                            </span>
                                            <input type="text" name="rif" class="form-control" id="yourUsername"
                                                placeholder="Ingrese rif" value="{{ $po->rif ?? $request->rif }}" required>
                                            <div class="invalid-feedback">Por favor, ingrese rif </div>
                                            @error('rif')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-sm-12 col-xs-12">
                                        {{-- <label for="floatingTextarea" class="form-label">Dirección o Domicilio de la empresa</label> --}}
                                        <div class="input-group has-validation">
                                            <div class="form-floating mb-3">
                                                <textarea class="form-control" name="direccion" placeholder="Leave a comment here" id="invalidTextarea" style="height: 100px;" required>{{ $po->direccion }}</textarea>
                                                <label for="invalidTextarea">Dirección de la empresa</label>
                                            </div>
                                            <div class="invalid-feedback">Por favor, ingrese domicilio </div>
                                            @error('rif')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <label for="yourUsername" class="form-label">Código Postal</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                <i class="bx bx-area"></i>
                                            </span>
                                            <input type="text" name="postal" class="form-control" id="yourUsername"
                                                placeholder="Ingrese rif" value="{{ $po->postal ?? $request->postal }}" required>
                                            <div class="invalid-feedback">Por favor, ingrese rif </div>
                                            @error('rif')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                    </div>

                                    
                                    <div class="col-sm-6 col-xs-12">
                                        <label for="foto" class="form-label">Subir Logo (Opcional)</label>
                                        <input type="file" name="file" class="form-control " id="file"
                                          accept="image/*"
                                        >
                                      
                                        @error('file')
                                          <div class="text-danger">
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span></div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12 col-xs-12 text-center">
                                        <label for="logo">Logo para las facturas:</label>
                                        <img src="{{ $po->imagen }}" width="200" class="img-fluid rounded" alt=""> 
                                    </div>
                                    <div class="col-6"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check form-switch text-center">
                                            <input class="form-check-input" name="estatusImagen" type="checkbox" id="flexSwitchCheckChecked" 
                                            {{ $po->estatusImagen ?  "checked" : ""}}>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Mostrar Logo en la factura</label>
                                        </div>
                                    </div>




                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Guardar Datos</button>
                                    </div>

                                </form>

                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </section>
    @endsection

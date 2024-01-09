@extends('layouts.app')

@section('title', 'Utilidades')


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
                                    <h5 class="card-title text-center pb-0 fs-2">Editar utilidades del sistema</h5>
                                    <p class="text-center text-danger small">Rellene todos los campos</p>
                                </div>

                                @if (count($utilidades))
                                    @php
                                        $utilidades = $utilidades[0];
                                    @endphp
                                    <form action="{{ route('admin.utilidades.update', $utilidades->id) }}" method="post" 
                                        enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                        @csrf
                                        @method('put')
                                        <div class="col-sm-6 col-xs-12">
                                            <label for="yourUsername" class="form-label">Tasa BCV</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    <i class="bx bx-money-withdraw"></i>
                                                </span>
                                                <input type="number" name="tasa"  step="any" class="form-control" id="yourUsername"
                                                    placeholder="Ingrese tasa BCV"
                                                    value="{{ number_format($utilidades->tasa, 2) ?? $request->tasa }}" required>
                                                <div class="invalid-feedback">Por favor, ingrese tasa del BCV! </div>
                                            </div>
                                            @error('tasa')
                                                <br>
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label for="yourUsername" class="form-label">% IVA</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    %
                                                </span>
                                                <input type="number" name="iva" class="form-control" id="yourUsername"
                                                    placeholder="Ingrese porcentaje de iva global"
                                                    value="{{ number_format($utilidades->iva, 2) ?? $request->iva }}" required>
                                                <div class="invalid-feedback">Por favor, ingrese porcentaje de IVA! </div>
                                            </div>
                                            @error('iva')
                                                <br>
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>


                                        {{-- <div class="col-sm-4 col-xs-12">
                                            <label for="yourUsername" class="form-label">% Utilidad PVP 1</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    %
                                                </span>
                                                <input type="number" name="pvp_1" class="form-control" id="yourUsername"
                                                    placeholder="Ingrese porcentaje global"
                                                    value="{{ number_format($utilidades->pvp_1, 2) ?? $request->tasa }}"
                                                    required>
                                                <div class="invalid-feedback">Por favor, ingrese porcentaje! </div>
                                                @error('pvp_1')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-xs-12">
                                            <label for="yourUsername" class="form-label">% Utilidad PVP 2</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    %
                                                </span>
                                                <input type="number" name="pvp_2" class="form-control" id="yourUsername"
                                                    placeholder="Ingrese porcentaje global"
                                                    value="{{ number_format($utilidades->pvp_2, 2) ?? $request->tasa }}"
                                                    required>
                                                <div class="invalid-feedback">Por favor, ingrese porcentaje! </div>
                                                @error('pvp_2')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-xs-12">
                                            <label for="yourUsername" class="form-label">% Utilidad PVP 3</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                    %
                                                </span>
                                                <input type="number" name="pvp_3" class="form-control" id="yourUsername"
                                                    placeholder="Ingrese porcentaje global"
                                                    value="{{ number_format($utilidades->pvp_3, 2) ?? $request->tasa }}"
                                                    required>
                                                <div class="invalid-feedback">Por favor, ingrese porcentaje! </div>
                                                @error('pvp_3')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Guardar Datos</button>
                                        </div>

                                    </form>
                                @else
                                <form action="{{ route('admin.utilidades.store')}}" method="post"
                                    enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                    @csrf
                                    @method('POST')
                                    <div class="col-sm-6 col-xs-12">
                                        <label for="yourUsername" class="form-label">Tasa</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                <i class="bx bx-money-withdraw"></i>
                                            </span>
                                            <input type="number" name="tasa"  step="any" class="form-control" id="yourUsername"
                                                placeholder="Ingrese tasa"
                                                value="{{ $request->tasa ?? '' }}"
                                                required>
                                            <div class="invalid-feedback">Por favor, ingrese tasa! </div>
                                        </div>
                                        @error('tasa')
                                            <br>
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <label for="yourUsername" class="form-label">% IVA</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                %
                                            </span>
                                            <input type="number" name="iva" class="form-control" id="yourUsername"
                                                placeholder="Ingrese porcentaje de iva global"
                                                value="{{ $request->iva ?? '' }}"
                                                required>
                                            <div class="invalid-feedback">Por favor, ingrese porcentaje de IVA! </div>
                                        </div>
                                        @error('iva')
                                            <br>
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>


                                    {{-- <div class="col-sm-4 col-xs-12">
                                        <label for="yourUsername" class="form-label">% Utilidad PVP 1</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                %
                                            </span>
                                            <input type="number" name="pvp_1" class="form-control" id="yourUsername"
                                                placeholder="Ingrese porcentaje global"
                                                value="{{ $request->pvp_1 ?? 0 }}"
                                                required>
                                            <div class="invalid-feedback">Por favor, ingrese porcentaje! </div>
                                            @error('pvp_1')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-12">
                                        <label for="yourUsername" class="form-label">% Utilidad PVP 2</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                %
                                            </span>
                                            <input type="number" name="pvp_2" class="form-control" id="yourUsername"
                                                placeholder="Ingrese porcentaje global"
                                                value="{{ $request->pvp_2 ?? 0 }}"
                                                required>
                                            <div class="invalid-feedback">Por favor, ingrese porcentaje! </div>
                                            @error('pvp_2')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <label for="yourUsername" class="form-label">% Utilidad PVP 3</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                                %
                                            </span>
                                            <input type="number" name="pvp_3" class="form-control" id="yourUsername"
                                                placeholder="Ingrese porcentaje global"
                                                value="{{ $request->pvp_3 ?? 0 }}"
                                                required>
                                            <div class="invalid-feedback">Por favor, ingrese porcentaje! </div>
                                            @error('pvp_3')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        </div>
                                    </div> --}}

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Guardar Datos</button>
                                    </div>

                                </form>
                                @endif


                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </section>
    @endsection

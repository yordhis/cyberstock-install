<!-- Vertically centered Modal -->
<button type="button" class="nav-item text-green bg-primary p-2 rounded-2 " data-bs-toggle="modal"
    data-bs-target="#modalCrear" >
    <i class="bi bi-filter-square "></i> Agregar Activo
</button>

<div class="modal fade" id="modalCrear" tabindex="-1"  >

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registrar Activo inmobiliario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.activos.store') }}" method="post" enctype="multipart/form-data"
                class=" g-3 needs-validation" novalidate>
                @csrf
                @method('post')

                <div class="modal-body">
                    <div class="row">
                        {{-- inputs CODIGO --}}
                        <div class="col-12 text-start ">
                            <label for="yourUsername" class="form-label">Código del activo inmobiliario</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-barcode-reader fs-3"></i>
                                </span>
                                <input type="text" name="codigo" class="form-control" id="codigo"
                                    placeholder="Ingrese código" value="{{ $request->codigo  ?? old('codigo') }}" required> <br>
                            </div>
                            @error('codigo')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- inputs DESCRIPCION --}}
                        <div class="col-12 text-start my-3">
                            <label for="yourUsername" class="form-label">Descrición del activo inmobiliario</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-box fs-3"></i>
                                </span>
                                <input type="text" name="descripcion" class="form-control" id="descripcion"
                                    placeholder="Ingrese descripción" value="{{ $request->descripcion  ?? old('descripcion') }}" required> <br>
                            </div>
                            @error('descripcion')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                         {{-- inputs UBICACION --}}
                        <div class="col-12 text-start my-3">
                            <label for="yourUsername" class="form-label">Ubicación del activo inmobiliario</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-address fs-3"></i>
                                </span>
                                <input type="text" name="ubicacion" class="form-control" id="ubicacion"
                                    placeholder="Ingrese ubicación, Ejemplo: Local 1, departamento de venta" value="{{ $request->ubicacion ?? old('ubicacion')  }}" required> <br>
                            </div>
                            @error('ubicacion')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                         {{-- inputs FECHA --}}
                        <div class="col-12 text-start my-3">
                            <label for="yourUsername" class="form-label">Fecha de compra</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-date fs-3"></i>
                                </span>
                                <input type="date" name="fecha_compra" class="form-control" id="fecha_compra"
                                   value="{{ $request->fecha_compra ?? old('fecha_compra')  }}" required> <br>
                            </div>
                            @error('fecha_compra')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                         {{-- inputs CANTIDAD --}}
                        <div class="col-xs-12 col-sm-6 text-start my-3">
                            <label for="yourUsername" class="form-label">Cantidad en existencia</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-date fs-3"></i>
                                </span>
                                <input type="number" name="cantidad" class="form-control" id="cantidad"
                                    placeholder="Ingrese cantidad"
                                   value="{{ $request->cantidad ?? old('cantidad')  }}" required> <br>
                            </div>
                            @error('cantidad')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                         {{-- inputs COSTO --}}
                        <div class="col-xs-12 col-sm-6 text-start my-3">
                            <label for="yourUsername" class="form-label">Valor del inmobiliario</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-date fs-3"></i>
                                </span>
                                <input type="number" name="costo" class="form-control" id="costo"
                                    placeholder="Ingrese costo"
                                   value="{{ $request->costo ?? old('costo')  }}" required> <br>
                            </div>
                            @error('costo')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-12 text-start">
                            <label for="yourUsername" class="form-label">¿Está operativo el inmoviliario?</label>
                            @error('estatus')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-1">
                            <div class="form-check form-switch">
                              
                                <input class="form-check-input" name="estatus" type="checkbox" id="estatus" value="1"
                                {{ old('estatus') ?  "checked" : ""}}
                                >
                                <label class="form-check-label" for="flexSwitchCheckDefault">SI</label>
                            </div>
                        </div>


                    </div>
                </div> <!--Fin div body-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary ">Guardar Datos</button>
                </div>
            </form>
        </div>
    </div>




</div><!-- End Vertically centered Modal-->

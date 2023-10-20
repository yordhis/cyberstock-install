<!-- Vertically centered Modal -->
<a type="button" class="nav-item " data-bs-toggle="modal" data-bs-target="#modalEditar{{ $proveedore->id }}">
    <i class="bx bx-pencil text-warning fs-5"></i>
</a>



<div class="modal fade modal-lg " style="" id="modalEditar{{ $proveedore->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="/proveedores/{{ $proveedore->id }}" method="post" target="_self" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('put')

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            @isset($respuesta['activo'])
                                @include('partials.alert')
                            @endisset
                        </div>
                        {{-- Titulo Dato empresariales --}}
                        <div class="col-12 text-start mt-2">
                            <h3>Datos Empresariales</h3>
                            <hr>
                        </div>

                        {{-- inputs tipo de documento --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Tipo</label> <span class="text-danger">*</span>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-book fs-3"></i>
                                </span>

                                <select name="tipo_documento" class="form-control" required>
                                    <option value="">Seleccione Tipo de documento</option>
                                    @isset($proveedore->tipo_documento)
                                        <option value="{{ $proveedore->tipo_documento }}" selected>
                                            {{ $proveedore->tipo_documento }}</option>
                                    @endisset
                                    @error('tipo_documento')
                                        <option value="{{ old('tipo_documento') }}" selected>{{ old('tipo_documento') }}
                                        </option>
                                    @enderror
                                    <option value="V">V</option>
                                    <option value="J">J</option>

                                </select>

                                <div class="invalid-feedback">Por favor ingrese El Código del proveedor! </div>
                            </div>

                            @error('tipo_documento')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- inputs codigo --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Código o Documento</label> <span
                                class="text-danger">*</span>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-code-alt fs-3"></i>
                                </span>

                                <input type="text" name="codigo" class="form-control" id="yourUsername"
                                    placeholder="Ingrese código del proveedor"
                                    value="@error('codigo') {{ old('codigo') }} @enderror {{ $proveedore->codigo ?? '' }}" required>
                                <div class="invalid-feedback">Por favor ingrese El Código del proveedor! </div>
                            </div>

                            @error('codigo')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- inputs Empresa --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Empresa</label> <span
                                class="text-danger">*</span>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-store-alt fs-3"></i>
                                </span>

                                <input type="text" name="empresa" class="form-control" id="empresa"
                                    placeholder="Ingrese Nombre de la Empresa" value="{{ $proveedore->empresa ?? '' }}"
                                    required>
                                <div class="invalid-feedback">Por favor ingrese El Nombre de la Empresa! </div>
                            </div>
                            @error('empresa')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- inputs Rubro --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Rubro</label> <span
                                class="text-danger">*</span>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-bookmarks fs-3"></i>
                                </span>

                                <input type="text" name="rubro" class="form-control" id="rubro"
                                    placeholder="Ingrese el rubro de productos" value="{{ $proveedore->rubro ?? '' }}"
                                    required>
                                <div class="invalid-feedback">Por favor, ingrese el rubro de productos! </div>
                            </div>
                            @error('rubro')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- inputs Dirección --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Dirección de la empresa</label> <span
                                class="text-danger">*</span>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-location-plus fs-3"></i>
                                </span>

                                <input type="text" name="direccion" class="form-control" id="direccion"
                                    placeholder="Ingrese dirección del domicilio."
                                    value="{{ $proveedore->direccion ?? '' }}" required>
                                <div class="invalid-feedback">Por favor ingrese, dirección del domicilio! </div>
                            </div>
                            @error('direccion')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Imagen --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="foto" class="form-label ">Subir Foto del proveedor (Opcional)</label>
                            <input type="file" name="file" class="form-control " id="foto">
                            <div class="invalid-feedback">Ingrese una imagen valida</div>
                            @error('file')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-6 text-end" style="height: 100px;">
                            <img src="{{ $proveedore->imagen ?? '' }}" class="img-thumbnail rounded d-block h-75 mt-2" alt="logo">
                        </div>

                        {{-- Titulo Dato empresariales --}}
                        <div class="col-12 text-start mt-4">
                            <h3>Datos de Contacto</h3>
                            <hr>
                        </div>

                        {{-- inputs Contacto --}}

                        <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Contacto</label> <span
                                class="text-danger">*</span>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-user-pin fs-3"></i>
                                </span>

                                <input type="text" name="contacto" class="form-control" id="contacto"
                                    placeholder="Ingrese Nombre del contacto, ejemplo: Julio Vergara."
                                    value="{{ $proveedore->contacto ?? '' }}" required>
                                <div class="invalid-feedback">Por favor ingrese El Nombre de la contacto! </div>
                            </div>
                                @error('contacto')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                        </div>

                        {{-- inputs Telefono --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Teléfono</label> <span
                                class="text-danger">*</span>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bxs-phone-call fs-3"></i>
                                </span>

                                <input type="phone" name="telefono" class="form-control" id="telefono"
                                    placeholder="Ingrese Número del teléfono"
                                    value="{{ $proveedore->telefono ?? '' }}" required>
                                <div class="invalid-feedback">Por favor ingrese El Número de la teléfono! </div>
                            </div>
                            @error('telefono')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                        {{-- inputs Correo --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Correo (Opcional)</label>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-mail-send fs-3"></i>
                                </span>

                                <input type="email" name="correo" class="form-control" id="correo"
                                    placeholder="Ingrese correo " value="{{ $proveedore->correo ?? '' }}">
                                <div class="invalid-feedback">Por favor, ingrese correo! </div>
                            </div>
                            @error('correo')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- inputs Edad --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Edad (Opcional)</label>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-happy fs-3"></i>
                                </span>

                                <input type="number" name="edad" class="form-control" id="edad"
                                    placeholder="Ingrese edad." value="{{ $proveedore->edad ?? '' }}">
                                <div class="invalid-feedback">Por favor ingrese la edad! </div>
                            </div>
                            @error('edad')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- inputs Nacimiento --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Cumpleaños (Opcional)</label>
                            <div class="input-group has-validation">

                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-calendar-event fs-3"></i>
                                </span>

                                <input type="date" name="nacimiento" class="form-control" id="nacimiento"
                                    placeholder="Ingrese Nombre del nacimiento, ejemplo: Julio Vergara."
                                    value="{{ $proveedore->nacimiento ?? '' }}">
                                <div class="invalid-feedback">Por favor ingrese Fecha nacimiento! </div>
                            </div>
                            @error('nacimiento')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                    </div>
                </div> <!--Fin div body-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary ">Guardar Datos</button>
                </div>
            </form>
        </div>
    </div>




</div><!-- End Vertically centered Modal-->

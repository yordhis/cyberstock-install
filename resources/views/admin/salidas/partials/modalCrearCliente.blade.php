<!-- Vertically centered Modal -->
<button type="button" class="btn btn-primary h-25 mt-4 mb-2 me-2  " data-bs-toggle="modal"
    data-bs-target="#modalCrear" >
    <i class="bi bi-person-add "></i> Agregar Cliente
</button>



<div class="modal fade" style="" id="modalCrear" tabindex="-1"  >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Crear Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="/pos/clientes" method="post" target="_self" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('post')

                <input type="hidden" name="formulario" value="modalCrearClienteSalida">
                
                <div class="modal-body">
                    <div class="row">
                        {{-- inputs nombre  --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Nombres y apellidos <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-user fs-3"></i>
                                </span>
    
                                <input type="text" name="nombre" class="form-control" id="yourUsername"
                                    placeholder="Ingrese Nombres y apellidos" value="{{ $request->nombre ?? '' }}" required>
                                <div class="invalid-feedback">Por favor,  Ingrese Nombres y Apellidos </div>

                                @error('nombre')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- inputs Tipo  --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Tipo <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bxs-caret-down-circle fs-3"></i>
                                </span>

                                <select name="tipo" id="tipo" class="form-select"  required>
                                    <option value="">Seleccione tipo de contribuyente</option>
                                    <option value="V">V</option>
                                    <option value="J">J</option>
                                </select>
    
                               

                                @error('tipo')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        {{-- inputs Identidficación  --}}
                        <div class="col-12 text-start mt-2 ">
                            <label for="yourUsername" class="form-label">Rif o Documento de identidad <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-id-card fs-3"></i>
                                </span>
    
                                <input type="text" name="identificacion" class="form-control" id="identificacion"
                                    placeholder="Ingrese identificación" value="{{ $request->identificacion ?? '' }}" required>
                                <div class="invalid-feedback">Por favor,  Ingrese identificacion </div>

                                @error('identificacion')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- inputs correo  --}}
                        <div class="col-12 text-start mt-2 ">
                            <label for="yourUsername" class="form-label">Correo <span class="text-warning">(Opcional)</span></label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-mail-send fs-3"></i>
                                </span>
    
                                <input type="text" name="correo" class="form-control" id="correo"
                                    placeholder="Ingrese correo" value="{{ $request->correo ?? '' }}" >
                                <div class="invalid-feedback">Por favor,  Ingrese correo </div>

                                @error('correo')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- inputs telefono  --}}
                        <div class="col-12 text-start mt-2 ">
                            <label for="yourUsername" class="form-label">Teléfono o movil <span class="text-warning">(Opcional)</span></label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-phone-call fs-3"></i>
                                </span>
    
                                <input type="text" name="telefono" class="form-control" id="telefono"
                                    placeholder="Ingrese telefono" value="{{ $request->telefono ?? '' }}">
                                <div class="invalid-feedback">Por favor,  Ingrese telefono </div>

                                @error('telefono')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- inputs direccion  --}}
                        <div class="col-12 text-start mt-2 ">
                            <label for="yourUsername" class="form-label">Dirección <span class="text-warning">(Opcional)</span></label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-current-location fs-3"></i>
                                </span>
    
                                <input type="text" name="direccion" class="form-control" id="direccion"
                                    placeholder="Ingrese direccion" value="{{ $request->direccion ?? '' }}" >
                                <div class="invalid-feedback">Por favor,  Ingrese direccion </div>

                                @error('direccion')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
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

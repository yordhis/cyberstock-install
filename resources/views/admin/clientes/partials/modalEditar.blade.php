
        
<!-- Vertically centered Modal -->
<a  class="text-white" data-bs-toggle="modal" data-bs-target="#modalEditarCliente{{$cliente->id}}">
    <i class="bi bi-pencil btn btn-warning"></i>
</a>
    


<div class="modal fade" id="modalEditarCliente{{$cliente->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Editar cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
     
            <div class="modal-body">
                <form action=" {{ route('admin.clientes.update', $cliente->id) }}" method="post" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('put')

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
                                    placeholder="Ingrese Nombres y apellidos" value="{{ $cliente->nombre ?? '' }}" required>
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
                                    @isset($cliente->tipo)
                                        <option value="{{ $cliente->tipo }}" selected>{{ $cliente->tipo }}</option>
                                    @endisset
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
                                    placeholder="Ingrese identificación" value="{{ $cliente->identificacion ?? '' }}" required>
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
                                    placeholder="Ingrese correo" value="{{ $cliente->correo ?? '' }}" >
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
                                    placeholder="Ingrese telefono" value="{{ $cliente->telefono ?? '' }}">
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
                                    placeholder="Ingrese direccion" value="{{ $cliente->direccion ?? '' }}" >
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary ">Guardar Datos</button>
                </div>
            </form>
            </div>
            

    </div>
    </div>

  

    
</div><!-- End Vertically centered Modal-->





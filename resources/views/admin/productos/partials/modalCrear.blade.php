<!-- Vertically centered Modal -->
<button type="button" class="nav-item text-green bg-primary p-2 rounded-2 " data-bs-toggle="modal"
    data-bs-target="#modalCrear" >
    <i class="bi bi-box "></i> Agregar Nuevo Producto
</button>



<div class="modal fade {{ isset($request->codigo) ? "show" : ""}} " style="{{ isset($request->codigo) ? "display: block;" : "display: none;"}}" id="modalCrear" tabindex="-1"  >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Crear Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="/productos" method="post" target="_self" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('post')

                <div class="modal-body">
                    <div class="row">

                        {{-- inputs codigo --}}
                        <div class="col-12 text-start ">
                            <label for="yourUsername" class="form-label">Código de barra</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-barcode-reader fs-3"></i>
                                </span>
    
                                <input type="text" name="codigo" class="form-control" id="yourUsername"
                                    placeholder="Ingrese código de barra" autofocus="true" value="{{ $request->codigo ?? '' }}" required>
                                <div class="invalid-feedback">Por favor ingrese El Código de barra! </div>
                            </div>
                        </div>
    
                        {{-- inputs Descripcion --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Descripción del producto</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-checkbox-minus fs-3"></i>
                                </span>
    
                                <input type="text" name="descripcion" class="form-control" id="descripcion"
                                    placeholder="Ingrese Descripción del producto" value="{{ $request->descripcion ?? '' }}"
                                    required>
                                <div class="invalid-feedback">Por favor ingrese El Descripción del producto! </div>
                            </div>
                        </div>
                       
    
                        {{-- Select Categoria --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourName" class="form-label">Asigne Categoria</label>
                            <select name="id_categoria" id="id_categoria" class="form-select" required>
                                <option value="">Seleccione Categoria</option>
                                @foreach ($categorias as $categoria)
                                    @isset($request->id_categoria)
                                        @if ($request->id_categoria == $categoria->id)
                                            <option value="{{ $categoria->id }}" selected>{{ $categoria->nombre }}
                                            </option>
                                        @endif
                                    @endisset
    
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
    
    
                            </select>
                            <div class="invalid-feedback">Por favor, Seleccione el Rol del usuario!</div>
                        </div>
    
                        {{-- Select Marcas --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourName" class="form-label">Asigne Marca</label>
                            <select name="id_marca" id="id_marca" class="form-select" required>
                                <option value="">Seleccione Marca</option>
                                @foreach ($marcas as $marca)
                                    @isset($request->id_marca)
                                        @if ($request->id_marca == $marca->id)
                                            <option value="{{ $marca->id }}" selected>{{ $marca->nombre }}
                                            </option>
                                        @endif
                                    @endisset
    
                                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                @endforeach
    
    
                            </select>
                            <div class="invalid-feedback">Por favor, Seleccione el Rol del usuario!</div>
                        </div>

                         {{-- inputs Fecha de vencimiento --}}
                         {{-- <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Fecha de vencimiento (Opcional)</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-time fs-3"></i>
                                </span>
    
                                <input type="date" name="fecha_vencimiento" class="form-control" id="fecha_vencimiento"
                                    placeholder="Ingrese Fecha de vencimiento" value="{{ $request->fecha_vencimiento ?? '' }}"
                                >
                                <div class="invalid-feedback">Por favor ingrese El Fecha de vencimiento! </div>
                            </div>
                        </div> --}}

                        {{-- Imagen --}}
                        <div class="col-12 text-start mt-2">
                            <label for="foto" class="form-label ">Subir Foto del producto (Opcional)</label>
                            <input type="file" name="file" class="form-control " id="foto">
                            <div class="invalid-feedback">Ingrese una imagen valida</div>
                        </div>

                        <div class="col-12">
                            <hr>
                            <p class="text-start text-mute">
                                Los siguientes datos solicitados son opcionales; 
                                pero al ingresar una cantidad se procesara una entrada en el Inventario automaticamente.
                            </p>
                        </div>
    
                        {{-- inputs Cantidad Inicial --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Cantidad Inicial (opcional)</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-checkbox-minus fs-3"></i>
                                </span>
    
                                <input type="number" name="cantidad_inicial" class="form-control" id="cantidad_inicial"
                                    placeholder="Ingrese cantidad inical del producto"
                                    value="{{ $request->cantidad_inicial ?? '' }}">
                                <div class="invalid-feedback">Por favor ingrese El cantidad inical del producto! </div>
                            </div>
                        </div>
    
                        {{-- inputs Costo --}}
                        <div class="col-sm-6 col-xs-12 text-start mt-2">
                            <label for="costo" class="form-label">Costo (opcional)</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="costo">
                                    <i class="bx bx-money fs-3"></i>
                                </span>
    
                                <input type="float" step="any" name="costo" class="form-control"
                                    id="costo" placeholder="Ingrese costo proveedor"
                                    value="{{ $request->costo ?? '' }}">

                                @error('costo')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                                @isset($mensaje)
                                    <div class="{{ $mensaje['input'] == 'costo' ? 'text-danger' : "" }}">{{ $mensaje['input'] == 'costo' ? $mensaje['texto'] : '' }} </div>                                
                                @endisset
                            </div>
                        </div>
    
                        {{-- inputs PVP --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Precio de Venta al público (PVP)</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-checkbox-minus fs-3"></i>
                                </span>
    
                                <input type="number" name="pvp" step="any" class="form-control"
                                    id="pvp" placeholder="Ingrese PVP"
                                    value="{{ $request->pvp ?? '' }}">
                                <div class="invalid-feedback">Por favor ingrese El PVP! </div>
                            </div>
                        </div>

                        {{-- inputs Utilidad --}}
                        {{-- <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Utilidad personalizada (opcional)</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-checkbox-minus fs-3"></i>
                                </span>
    
                                <input type="number" name="utilidad_personalizada" class="form-control"
                                    id="utilidad_personalizada" placeholder="Ingrese utilidad"
                                    value="{{ $request->utilidad_personalizada ?? '' }}">
                                <div class="invalid-feedback">Por favor ingrese El utilidad! </div>
                            </div>
                        </div> --}}
                        
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


        
<!-- Vertically centered Modal -->
<a type="button" class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#modalEditarProducto{{$producto->id}}">
    <i class="bi bi-pencil "></i>
</a>
    


<div class="modal fade" id="modalEditarProducto{{$producto->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Editar producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
     
            <div class="modal-body">
                <form action="{{ route('admin.productos.update', $producto->id) }}" method="post" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('PUT')

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
                                    placeholder="Ingrese código de barra" value="{{ $producto->codigo  ?? '' }}" required>
                                <div class="invalid-feedback">Por favor ingrese El Código de barra! </div>
                            </div>
                            @error('codigo')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
    
                        {{-- inputs Descripcion --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Descripción del producto</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-checkbox-minus fs-3"></i>
                                </span>
    
                                <input type="text" name="descripcion" class="form-control" id="descripcion"
                                    placeholder="Ingrese Descripción del producto" value="{{ $producto->descripcion ?? '' }}"
                                    required>
                                <div class="invalid-feedback">Por favor ingrese El Descripción del producto! </div>
                            </div>
                            @error('descripcion')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                       
    
                        {{-- Select Categoria --}}
                        <div class="col-12 text-start mt-2">
                            <label for="yourName" class="form-label">Asigne Categoria</label>
                            <select name="id_categoria" id="id_categoria" class="form-select" required>
                                <option value="">Seleccione Categoria</option>
                                @foreach ($categorias as $categoria)

                                    @isset($producto->id_cat)
                                        @if ($producto->id_cat == $categoria->id)
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

                                    @isset($producto->id_mar)
                                        @if ($producto->id_mar == $marca->id)
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
                         <div class="col-12 text-start mt-2">
                            <label for="yourUsername" class="form-label">Fecha de vencimiento (Opcional)</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                    <i class="bx bx-time fs-3"></i>
                                </span>
    
                                <input type="date" name="fecha_vencimiento" class="form-control" id="fecha_vencimiento"
                                    placeholder="Ingrese Fecha de vencimiento" value="{{ $producto->fecha_vencimiento ??  '' }}"
                                >
                                <div class="invalid-feedback">Por favor ingrese El Fecha de vencimiento! </div>
                            </div>
                            @error('fecha_vencimiento')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Imagen --}}
                        <div class="col-12 text-start mt-2">
                            <label for="foto" class="form-label ">Subir Foto del producto (Opcional)</label>
                            <input type="file" name="file" class="form-control " id="foto">
                            <div class="invalid-feedback">Ingrese una imagen valida</div>
                        </div>

                        <div class="col-12 mt-2">
                            <img src="{{ asset($producto->imagen)  ?? '' }}"  alt="imagen" class="img-thumbnail w-50">
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





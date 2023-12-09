@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div id="alert"></div>
<section class="section">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-sm-8">
            <form action="/productos/{{$producto->id}}" method="post" target="_self" enctype="multipart/form-data"
               class=" g-3 needs-validation ">
               @csrf
               @method('put')
       
               <div class="card p-2">
                   <div class="row ">
       
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
                                   placeholder="Ingrese Descripción del producto"
                                   value="{{ $producto->descripcion ?? '' }}" required>
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
                                   
                                   @isset($producto->id_categoria->id)
                                       @if ($producto->id_categoria->id == $categoria->id)
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
       
                                   @isset($producto->id_marca->id)
                                       @if ($producto->id_marca->id == $marca->id)
                                           <option value="{{ $marca->id }}" selected>{{ $marca->nombre }}
                                           </option>
                                       @endif
                                   @endisset
       
                                   <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                               @endforeach
       
       
                           </select>
                           <div class="invalid-feedback">Por favor, Seleccione el Rol del usuario!</div>
                       </div>
       
                       {{-- Imagen --}}
                       <div class="col-12 text-start mt-2">
                           <label for="foto" class="form-label ">Subir Foto del producto (Opcional)</label>
                           <input type="file" name="file" class="form-control " id="foto">
                           <div class="invalid-feedback">Ingrese una imagen valida</div>
                       </div>
                       <div class="col-12 mt-2 ">
                           {{-- <img src="{{ $producto->imagen ?? '' }}" alt="imagen" class="img-thumbnail"> --}}
                       </div>
       
                       <div class="col-12">
                           <hr>
                           <p class="text-start text-mute">
                               Los siguientes datos solicitados son opcionales; y pertenecen a una entrada inicial del
                               inventario si desea actualizar afectará los datos de inventario de este producto.
                           </p>
                       </div>
       
                       {{-- inputs Cantidad Inicial --}}
                       <div class="col-sm-6 col-xs-12 text-start mt-2">
                           <label for="yourUsername" class="form-label">Cantidad Inicial (opcional)</label>
                           <div class="input-group has-validation">
       
                               <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                   <i class="bx bx-checkbox-minus fs-3"></i>
                               </span>
       
                               <input type="number" name="cantidad_inicial" step="any" class="form-control"
                                   id="cantidad_inicial" placeholder="Ingrese cantidad inical del producto"
                                   value="{{ $producto->inventario->cantidad  ?? ''}}">
                               <div class="invalid-feedback">Por favor ingrese El cantidad inical del producto! </div>
                                   
                               @error('cantidad_inicial')
                                   <span class="text-danger">
                                       {{ $message }}
                                   </span>
                               @enderror
                           </div>
                       </div>
       
                       {{-- inputs Costo --}}
                       <div class="col-sm-6 col-xs-12 text-start mt-2">
                           <label for="yourUsername" class="form-label">Costo (opcional)</label>
                           <div class="input-group has-validation">
       
                               <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                   <i class="bx bx-checkbox-minus fs-3"></i>
                               </span>
       
                               <input type="number" name="costo" step="any" class="form-control" id="costo"
                                   placeholder="Ingrese costo proveedor" value="{{ $producto->inventario->costo ?? '' }}">
                               @isset($mensaje)
                               <div class="{{ $mensaje['input'] == 'costo' ? 'text-danger' : "" }}">{{ $mensaje['input'] ==
                                   'costo' ? $mensaje['texto'] : '' }} </div>
                               @endisset
       
                               @error('costo')
                               <span class="text-danger">
                                   {{ $message }}
                               </span>
                               @enderror
                           </div>
                       </div>
       
                       {{-- inputs Utilidad --}}
                       <div class="col-12 text-start mt-2">
                           <label for="yourUsername" class="form-label">Precio de Venta al público (PVP)</label>
                           <div class="input-group has-validation">
       
                               <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                   <i class="bx bx-building fs-3"></i>
                               </span>
       
                               <input type="number" name="pvp" step="any" class="form-control" id="pvp"
                                   placeholder="Ingrese PVP" value="{{ $producto->inventario->pvp ??   '' }}">
                               <div class="invalid-feedback">Por favor, ingrese El PVP! </div>
                           </div>
                       </div>
       
                   </div>
       
               </div>
               <!--Fin div body-->
       
               <div class="modal-footer m-2">
                   <a href="/productos" target="_self" class="btn btn-danger m-1">Volver a la lista de productos</a>
                   <button type="submit" class="btn btn-primary ">Guardar Datos</button>
               </div>
           </form>

        </div>
    </div>
</section>





@endsection
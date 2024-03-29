<!-- Vertically centered Modal -->
<button type="button" class="nav-item text-green bg-primary p-2 rounded-2 " data-bs-toggle="modal"
    data-bs-target="#modalCrear" >
    <i class="bi bi-filter-square "></i> Agregar categoria
</button>


@if ($errors->any())
    <div class="modal fade show"  id="modalCrear" tabindex="-1"  >
@else
    <div class="modal fade" style="display: none;" id="modalCrear" tabindex="-1"  >
@endif

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Crear Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.categorias.store') }}" method="post" enctype="multipart/form-data"
                class=" g-3 needs-validation">
                @csrf
                @method('post')

                <div class="modal-body">
                    <div class="row">
                        {{-- inputs nombre dela categoria --}}
                        <div class="col-12 text-start ">
                            <label for="yourUsername" class="form-label">Nombre de la categoria</label>
                            <div class="input-group has-validation">
    
                                <span class="input-group-text text-white bg-primary" id="yourUsername">
                                    <i class="bx bx-barcode-reader fs-3"></i>
                                </span>
    
                                <input type="text" name="nombre" class="form-control" id="yourUsername"
                                    placeholder="Ingrese nombre de la categoria" value="{{ $request->nombre ?? '' }}" required> <br>
                                <div class="invalid-feedback">Por favor ingrese El Código de barra! </div>

                                
                            </div>
                            @error('nombre')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>


                </div> <!--Fin div body-->

                <div class="modal-footer">
                    @if ($errors->any())
                        <a href="productos/{{$pathname}}" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @endif
                    <button type="submit" class="btn btn-primary ">Guardar Datos</button>
                </div>
            </form>
        </div>
    </div>




</div><!-- End Vertically centered Modal-->

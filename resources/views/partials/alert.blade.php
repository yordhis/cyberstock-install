
@isset($respuesta)
  <div class="row" id="alert">
    {{-- <div class="col-sm-8"></div> --}}
    <div class="col-sm-12 text-start">
      <div class="alert {{ $respuesta['clases'][ $respuesta['estatus'] ] }} alert-dismissible fade show" role="alert">
          <i class="{{ $respuesta['icono'][ $respuesta['estatus'] ] }}"></i>
          {{$respuesta['mensaje']}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>
@endisset

@php
  if(session('estatus')){
    $respuesta['estatus'] = session('estatus');
    $respuesta['mensaje'] = session('mensaje');
  }   
@endphp
@if (session('estatus'))

  <div class="row" id="alert">
    <div class="col-sm-8"></div>
    <div class="col-sm-4">
      <div class="alert {{ $respuesta['clases'][ $respuesta['estatus'] ] }} alert-dismissible fade show" role="alert">
          <i class="{{ $respuesta['icono'][ $respuesta['estatus'] ] }}"></i>
          {{$respuesta['mensaje']}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
    </div>
  </div>
@endif


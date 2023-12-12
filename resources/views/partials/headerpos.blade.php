<nav class="navbar navbar-light bg-light p-0 m-0" style="height: 0rem">
    <div class="container-fluid ">
      <a class="navbar-brand"  target="_self">
        <img src="{{ asset('/assets/img/logocs.png') }}" alt="" width="30" height="24" class="d-inline-block align-text-top">
       
        @if (count(explode('/', $pathname)) > 1)
            @switch(explode('/', $pathname)[1])
            @case('crearSalida')
                Pos {{'Inventario Procesar Salida'}}
                @break
            @case('crearEntrada')
                Pos {{'Inventario Procesar Entrada'}}
                @break
            @default
                Pos Venta
            @endswitch
        @else
          Pos Venta
        @endif
        
      </a>
    </div>
  </nav>
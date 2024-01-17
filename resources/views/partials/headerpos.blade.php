<nav class="navbar navbar-light bg-light p-0 m-0" style="height: auto;">
    <div class="container-fluid ">
      <a class="navbar-brand"  target="_self">
        <img src="{{ asset('/assets/img/logocs.png') }}" alt="" width="30" height="24" class="d-inline-block align-text-top">
       
        @if (count(explode('/', $pathname)) > 1)
            @switch(explode('/', $pathname)[1])
            @case('crearSalida')
                Pos {{'Ventas mayoreo'}}
                @break
            @case('crearEntrada')
                Pos {{'Compras de inventario'}}
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
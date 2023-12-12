@php
    $url = explode('/', $_SERVER['REQUEST_URI']);
    $categoria = $url[1];
    if (Str::length($url[1]) > 10) {
        $categoria = explode('?', $url[1])[0];
    } else {
        $categoria = $url[1];
    }

    if (isset($url[2])) {
        if (Str::length($url[2]) > 10) {
            $subcategoria = explode('?', $url[2])[0];
        } else {
            $subcategoria = $url[2];
        }
    } else {
        $subcategoria = '';
    }
@endphp

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="/panel" class="" target="_self">
            <img src="{{ asset('assets/img/logo_2.png') }}" height="50" width="" alt="">
            {{-- <span class="d-none d-lg-block">NiceAdmin</span> --}}
        </a>
        <i class="bi bi-list toggle-sidebar-btn text-white active"></i>
    </div><!-- End Logo -->



    <nav class="header-nav ms-auto">
        {{-- {{  Str::length($url[2]) }} --}}
        {{-- {{ $categoria . " - -- -- " . $subcategoria}} --}}
        <ul class="d-flex align-items-center">

            @foreach ($menuSuperior as $key => $item)
                @if ($key == $categoria)
                    @if ($categoria == 'inventarios')
                        @if (Auth::user()->rol == 1 || Auth::user()->rol == 2)   
                             <!-- Start Components Nav | GENERAL -->
                            <li class="nav-item">
                                <a class="nav-link p-2 me-2 {{ $subcategoria == "" ? "border bg-white text-black": 'text-white border-end' }}" 
                                        href="{{ route('admin.inventarios.index') }}">
                                    <span>GENERAL</span></i>
                                </a>
                            </li><!-- End Components Nav | GENERAL -->

                             <!-- Start Components Nav | MOVIMIENTOS DE SALIDAS -->
                            <li class="nav-item">
                                <a class="nav-link p-2 me-2 {{ $subcategoria == "listaSalidas" ? "border bg-white text-black": 'text-white border-end' }}" 
                                        href="{{ route('admin.inventarios.listaSalidas') }}">
                                    <span>SALIDAS</span></i>
                                </a>
                            </li><!-- End Components Nav | MOVIMIENTOS DE SALIDAS -->

                             <!-- Start Components Nav | MOVIMIENTOS DE ENTRADAS -->
                            <li class="nav-item">
                                <a class="nav-link p-2 me-2 {{ $subcategoria == "listaEntradas" ? "border bg-white text-black": 'text-white border-end' }}" 
                                        href="{{ route('admin.inventarios.listaEntradas') }}">
                                    <span>ENTRADAS</span></i>
                                </a>
                            </li><!-- End Components Nav | MOVIMIENTOS DE ENTRADAS -->

                             <!-- Start Components Nav | PROCESAR MOVIMIENTO DE SALIDA -->
                            <li class="nav-item">
                                <a class="nav-link p-2 me-2 {{ $subcategoria == "crearSalida" ? "border bg-white text-black": 'text-white border-end' }}" 
                                        href="{{ route('admin.inventarios.crearSalida') }}">
                                    <span>PROCESAR SALIDA</span></i>
                                </a>
                            </li><!-- End Components Nav | PROCESAR MOVIMIENTO DE SALIDA -->

                             <!-- Start Components Nav | PROCESAR MOVIMIENTO DE SALIDA -->
                            <li class="nav-item">
                                <a class="nav-link p-2 me-2 {{ $subcategoria == "crearEntrada" ? "border bg-white text-black": 'text-white border-end' }}" 
                                        href="{{ route('admin.inventarios.crearEntrada') }}">
                                    <span>PROCESAR ENTRADA</span></i>
                                </a>
                            </li><!-- End Components Nav | PROCESAR MOVIMIENTO DE ENTRADA -->
                        

                            {{-- @foreach ($item as $keyItem => $menu)
                                <li
                                    class="nav-item me-2 pe-2 {{ $menu == $subcategoria ? 'border p-2 bg-white text-black' : 'text-white border-end' }}">
                                    <a href="{{ $key . '/' . $menu }}" target="_self" class="nav-link">
                                        {{ $keyItem }}
                                    </a>
                                </li>
                            @endforeach --}}
                        @endif
                    @else
                        @foreach ($item as $keyItem => $menu )
                                
                                @php
                                    $categoria = $subcategoria ? $subcategoria : $categoria;
                                @endphp
                            <li
                                class="nav-item me-2 pe-2 {{ $keyItem ==  $categoria ? 'border p-2 bg-white text-black' : 'text-white border-end' }}">
                                <a href="{{ route("admin.{$keyItem}.{$menu}") }}" class="nav-link">
                                    {{ Str::upper($keyItem)  }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                @endif
            @endforeach


            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->foto ?? '' }}" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->nombre ?? '' }}</span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ Auth::user()->nombre }}</h6>
                        <span>{{ Auth::user()->rol ? 'Aministrador' : 'Asistente' }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>



                    <li>
                        <form action="{{ route('login.logout') }}" method="post" class="text-center" target="_self" id="cerrarSesion">
                            @csrf
                            @method('post')
                            <button type="bottom" class="btn btn-node" >
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Salir</span>
                            </button>
                            </a>
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

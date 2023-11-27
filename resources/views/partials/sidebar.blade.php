@php
$url = explode('/', $_SERVER['REQUEST_URI']);
$categoria = strtoupper($url[1]);
if (isset($url[2])) {
$subcategoria = strtoupper($url[2]);
} else {
$subcategoria = 'LISTA';
}
@endphp

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    
    <ul class="sidebar-nav" id="sidebar-nav">
        {{-- Permisos del vendedor --}}
        @if (Auth::user()->rol == 3)
          <!-- Start Components Nav | Panel -->
          <li class="nav-item">
            <a class="nav-link  {{ $categoria == "PANEL" ? "bg-white text-black": '' }}" href="/panel" target="_self" >
                <i class="bi bi-grid"></i>
                <span>PANEL </span>
            </a>
        </li><!-- End Dashboard Nav | Panel-->
            <!-- Start Components Nav | POS venta -->
            <li class="nav-item">
                <a class="nav-link" target="_self" href="pos">
                    <i class="bx bx-store"></i><span>POS</span></i>
                </a>
            </li><!-- End Components Nav | POS venta-->
            <!-- Start Components Nav | INVENTARIO -->
        {{-- <li class="nav-item">
            <a class="nav-link {{ $categoria == "INVENTARIOS" ? "bg-white text-black": '' }}" href="/inventarios" target="_self">
                <i class="bi bi-box"></i><span>INVENTARIO</span></i>
            </a>
        </li><!-- End Components Nav | INVENTARIO --> --}}

        <!-- Start Components Nav | CLIENTES -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "CLIENTES" ? "bg-white text-black": '' }}" href="/pos/clientes" target="_self">
                <i class="bi bi-box"></i><span>CLIENTES</span></i>
            </a>
        </li><!-- End Components Nav | CLIENTES -->

        <!-- Start Components Nav | FACTURAS -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "FACTURAS" ? "bg-white text-black": '' }}" href="/pos/facturas" target="_self">
                <i class="bi bi-box"></i><span>FACTURAS</span></i>
            </a>
        </li><!-- End Components Nav | FACTURAS -->

    

        @endif
        {{-- Cierre Permisos del vendedor --}}

        {{-- Permisos del user ROOT y administrador --}}
        @if (Auth::user()->rol == 1 || Auth::user()->rol == 2)
        <!-- Start Components Nav | Panel -->
        <li class="nav-item">
            <a class="nav-link  {{ $categoria == "PANEL" ? "bg-white text-black": '' }}" href="/panel" target="_self" >
                <i class="bi bi-grid"></i>
                <span>PANEL </span>
            </a>
        </li><!-- End Dashboard Nav | Panel-->

        <!-- Start Components Nav | INVENTARIO -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "INVENTARIOS" ? "bg-white text-black": '' }}" href="/inventarios" target="_self">
                <i class="bi bi-box"></i><span>INVENTARIO</span></i>
            </a>
        </li><!-- End Components Nav | INVENTARIO -->

        <!-- Start Components Nav | POS venta -->
        <li class="nav-item">
            <a class="nav-link" target="_self" href="pos">
                <i class="bx bx-store"></i><span>POS</span></i>
            </a>
        </li><!-- End Components Nav | POS venta-->

        <!-- Start Components Nav | Niveles de estudio -->
        <li class="nav-item">
            <a class="nav-link" target="_self" href="proveedores">
                <i class="bi bi-person-vcard-fill"></i><span>PROVEEDORES</span>
                {{-- <i class="bi bi-chevron-down ms-auto"></i> --}}
            </a>

        </li><!-- End Components Nav | Niveles de estudio -->

        <!-- Start Components Nav | Planes de Pago -->
        <li class="nav-item">
            <a class="nav-link" target="_self" href="/productos">
                <i class="bi bi-signpost-2"></i><span>PRODUCTOS</span></i>
            </a>
           
        </li><!-- End Components Nav | Planes de Pago -->

        <!-- Start Components Nav | CLIENTES -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "CLIENTES" ? "bg-white text-black": '' }}" href="/pos/clientes" target="_self">
                <i class="bi bi-person-badge"></i><span>CLIENTES</span></i>
            </a>
        </li><!-- End Components Nav | CLIENTES -->
        
        <!-- Start Components Nav | FACTURAS -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "FACTURAS" ? "bg-white text-black": '' }}" href="/pos/facturas" target="_self">
                <i class="bi bi-receipt"></i><span>FACTURAS</span></i>
            </a>
        </li><!-- End Components Nav | FACTURAS -->
     
        @endif

        @if (Auth::user()->rol == 1 || Auth::user()->rol == 2)
        <!-- Start Components Nav | configuraciones -->
        <li class="nav-item">
            <a class="nav-link {{ ($categoria == 'CONFIGURACIONES' ? 'collapse show' : $categoria == 'USUARIOS') ? 'collapse show' : 'collapsed' }}"
                data-bs-target="#components-nav-10" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>CONFIGURACIÓN</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav-10"
                class="nav-content  {{ ($categoria == 'CONFIGURACIONES' ? 'collapse show' : $categoria == 'USUARIOS') ? 'collapse show' : 'collapse' }} "
                data-bs-parent="#sidebar-nav">

                <!-- Start Components Nav | Utilidades -->
                <li class="nav-item">
                    <a class="nav-link"  href="/utilidades" target="_self">
                        <i class='bi bi-cash fs-3'></i><span>Configurar Utilidades</span>
                    </a>
                </li><!-- End Components Nav | Utilidades -->

                <!-- Start Components Nav | Metodos de pago -->
                {{-- <li class="nav-item">
                    <a class="nav-link" href="metodosPagos" target="_self">
                        <i class="bi bi-paypal fs-3"></i><span>Métodos de pago</span>
                    </a>
                </li><!-- End Components Nav | Metodos de pago --> --}}

                <!-- Start Components Nav | TIPOS DE TRANSACCIONES -->
                {{-- <li class="nav-item">
                    <a class="nav-link" href="tiposTransacciones" target="_self">
                        <i class="bi bi-paypal fs-3"></i><span>Tipo de Transacciones</span>
                    </a>
                </li><!-- End Components Nav | TIPOS DE TRANSACCIONES --> --}}

                <!-- Start Components Nav | usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="usuarios" target="_self">
                        <i class="bi bi-people fs-3"></i><span>Usuarios</span>
                    </a>
                </li><!-- End Components Nav | usuarios -->

                <!-- Start Components Nav | usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="pos/1" target="_self">
                        <i class="bi bi-tools fs-3"></i><span>Configurar POS</span>
                    </a>
                </li><!-- End Components Nav | usuarios -->

            </ul>
        </li><!-- End Components Nav | configuraciones -->
        @endif

    </ul>


</aside><!-- End Sidebar -->
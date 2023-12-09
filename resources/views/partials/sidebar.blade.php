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
            <a class="nav-link  {{ $categoria == "PANEL" ? "bg-white text-black": '' }}" href="{{ route('admin.panel.index') }}"  >
                <i class="bi bi-grid"></i>
                <span>PANEL </span>
            </a>
        </li><!-- End Dashboard Nav | Panel-->
            <!-- Start Components Nav | POS venta -->
            <li class="nav-item">
                <a class="nav-link"  href="{{ route('admin.pos.index') }}">
                    <i class="bx bx-store"></i><span>POS</span></i>
                </a>
            </li><!-- End Components Nav | POS venta-->
            <!-- Start Components Nav | INVENTARIO -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "INVENTARIOS" ? "bg-white text-black": '' }}" href="{{ route('vendedor.inventarios') }}" >
                <i class="bi bi-box"></i><span>INVENTARIO</span></i>
            </a>
        </li><!-- End Components Nav | INVENTARIO -->

        <!-- Start Components Nav | CLIENTES -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "CLIENTES" ? "bg-white text-black": '' }}" href="{{ route('admin.clientes.index') }}" >
                <i class="bi bi-box"></i><span>CLIENTES</span></i>
            </a>
        </li><!-- End Components Nav | CLIENTES -->

        <!-- Start Components Nav | FACTURAS -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "FACTURAS" ? "bg-white text-black": '' }}" href="{{ route('admin.facturas.index') }}" >
                <i class="bi bi-box"></i><span>FACTURAS</span></i>
            </a>
        </li><!-- End Components Nav | FACTURAS -->

        <!-- Start Components Nav | REPORTES -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "REPORTES" ? "bg-white text-black": '' }}" href="{{ route('vendedor.reportes') }}" >
                <i class='bx bx-receipt'></i><span>REPORTES</span></i>
            </a>
        </li><!-- End Components Nav | REPORTES -->

        @endif
        {{-- Cierre Permisos del vendedor --}}

        {{-- Permisos del user ROOT y administrador --}}
        @if (Auth::user()->rol == 1 || Auth::user()->rol == 2)
        <!-- Start Components Nav | Panel -->
        <li class="nav-item">
            <a class="nav-link  {{ $categoria == "PANEL" ? "bg-white text-black": '' }}" href="{{ route('admin.panel.index') }}"  >
                <i class="bi bi-grid"></i>
                <span>PANEL </span>
            </a>
        </li><!-- End Dashboard Nav | Panel-->

        <!-- Start Components Nav | INVENTARIO -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "INVENTARIOS" ? "bg-white text-black": '' }}" href="{{ route('admin.inventarios.index') }}" >
                <i class="bi bi-box"></i><span>INVENTARIO</span></i>
            </a>
        </li><!-- End Components Nav | INVENTARIO -->

        <!-- Start Components Nav | POS venta -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pos.index') }}">
                <i class="bx bx-store"></i><span>POS</span></i>
            </a>
        </li><!-- End Components Nav | POS venta-->

        <!-- Start Components Nav | PROVEEDORES -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.proveedores.index') }}">
                <i class="bi bi-person-vcard-fill"></i><span>PROVEEDORES</span>
            </a>
        </li><!-- End Components Nav | PROVEEDORES -->

        <!-- Start Components Nav | Planes de Pago -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "PRODUCTOS" ? "bg-white text-black": '' }}"  href="{{ route('admin.productos.index') }}">
                <i class="bi bi-signpost-2"></i><span>PRODUCTOS</span></i>
            </a>
           
        </li><!-- End Components Nav | Planes de Pago -->

        <!-- Start Components Nav | CLIENTES -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "CLIENTES" ? "bg-white text-black": '' }}" href="{{ route('admin.clientes.index') }}">
                <i class="bi bi-person-badge"></i><span>CLIENTES</span></i>
            </a>
        </li><!-- End Components Nav | CLIENTES -->
        
        <!-- Start Components Nav | FACTURAS -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "FACTURAS" ? "bg-white text-black": '' }}" href="{{ route('admin.facturas.index') }}">
                <i class="bi bi-receipt"></i><span>FACTURAS</span></i>
            </a>
        </li><!-- End Components Nav | FACTURAS -->

        <!-- Start Components Nav | REPORTES -->
        <li class="nav-item">
            <a class="nav-link {{ $categoria == "REPORTES" ? "bg-white text-black": '' }}" href="{{ route('admin.reportes.index') }}">
                <i class='bx bx-receipt'></i><span>REPORTES</span></i>
            </a>
        </li><!-- End Components Nav | REPORTES -->
     
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
                    <a class="nav-link"  href="{{ route('admin.utilidades.index') }}" >
                        <i class='bi bi-cash fs-3'></i><span >Configurar Utilidades</span>
                    </a>
                </li><!-- End Components Nav | Utilidades -->


                <!-- Start Components Nav | usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}" >
                        <i class="bi bi-people fs-3"></i><span>Usuarios</span>
                    </a>
                </li><!-- End Components Nav | usuarios -->

                <!-- Start Components Nav | usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.pos.show', 1) }} " >
                        <i class="bi bi-tools fs-3"></i><span>Configurar POS</span>
                    </a>
                </li><!-- End Components Nav | usuarios -->

            </ul>
        </li><!-- End Components Nav | configuraciones -->
        @endif

    </ul>


</aside><!-- End Sidebar -->
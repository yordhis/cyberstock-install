<li class="nav-item dropdown">

    <a class="nav-link nav-icon text-white" href="#" data-bs-toggle="dropdown">
      <i class="bi bi-bell"></i>
      <span class="badge bg-danger badge-number"> <span id="total_notificaciones_toolti">0</span>  </span>
    </a><!-- End Notification Icon -->
    
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header" >
          Tu tienes <span id="total_notificaciones"></span> nuevas notificaciones
          <a href="{{ route('admin.notificaciones.index') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todas</span></a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        
        <div id="listaDeNotificaciones">

        </div>
          
        <li class="dropdown-footer">
          <a href="{{ route('admin.notificaciones.index') }}">Ver todas las notificaiones</a>
        </li>

      </ul><!-- End Notification Dropdown Items -->
   

</li><!-- End Notification Nav -->

<script src="{{ asset('/js/notificaciones/notificaciones.js') }}" defer></script>
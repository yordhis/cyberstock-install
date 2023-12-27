  <!-- ======= Footer ======= -->
  <footer id="" class=" fixed-bottom w-100 text-white">
      <div class="text-center ">
          &copy; Copyright <strong><span>Cyber Staff C.A 2023</span></strong>. Todos los derechos reservados
      </div>
      <div class="text-center">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
          Diseñado por <a class="text-warning" href="https://cyberstaffstore.com/">cyberstaffstore.com</a>
      </div>
  </footer><!-- End Footer -->


  
  <!-- Template Main JS File -->
  <script src="{{ asset('/assets/js/main.js') }}" defer></script>
  <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>

<!-- PWA Script -->
<script src="{{ asset('/sw.js') }}" ></script>
<script>
   if ("serviceWorker" in navigator) {
      // Register a service worker hosted at the root of the
      // site using the default scope.
      navigator.serviceWorker.register("/sw.js").then(
      (registration) => {
         console.log("El registro del trabajador de servicios fue exitoso:", registration);
      },
      (error) => {
         console.error(`Error en el registro del trabajador del servicio: ${error}`);
      },
    );
  } else {
     console.error("Service workers are not supported.");
  }
</script>
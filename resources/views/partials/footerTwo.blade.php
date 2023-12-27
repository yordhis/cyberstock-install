  <!-- ======= Footer ======= -->

      <footer class="footer ">
          <div class="copyright text-black">
              &copy; Copyright <strong><span class="text-primary">Cyber Staff C.A 2023</span></strong>. Todos los derechos
              reservados
          </div>
          <div class="credits">
               <a class="text-primary" href="https://cyberstaffstore.com/">Diseñado por el Equipo de desarrollo Cyber Staff</a>
          </div>
      </footer><!-- End Footer -->




  <script src="{{ asset('/assets/js/main.js') }}" defer></script>
  <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
  
  <script src="{{ asset('/js/partials/alert.js') }}" defer></script>

  <!-- PWA Script -->
  <script src="{{ asset('/sw.js') }}"></script>
  <script>
     if ("serviceWorker" in navigator) {
        // Register a service worker hosted at the root of the
        // site using the default scope.
        navigator.serviceWorker.register("/sw.js").then(
        (registration) => {
           console.log("Service worker registration succeeded:", registration);
        },
        (error) => {
           console.error(`Service worker registration failed: ${error}`);
        },
      );
    } else {
       console.error("Service workers are not supported.");
    }
  </script>
  

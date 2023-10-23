  <!-- ======= Footer ======= -->
  <footer class="footer">
      <div class="copyright text-black">
          &copy; Copyright <strong><span class="text-primary">Cyber Staff C.A 2023</span></strong>. Todos los derechos
          reservados
      </div>
      <div class="credits ">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
          Diseñado por <a class="text-primary" href="https://cyberstaffstore.com/">Equipo de desarrollo Cyber Staff</a>
      </div>
  </footer><!-- End Footer -->





  <!-- Template Main JS File -->
  <script src="./js/partials/alert.js" defer></script>

  @if ($pathname == 'productos')
      <script src="./js/main.js" defer></script>
      <script src="./js/productos/productoController.js" defer></script>
  @endif

  @if ($pathname == 'pos/facturas')
  <script src="./js/main.js" defer></script>
  <script src="./js/facturas/facturaController.js" defer></script>
  <script src="./js/facturas/index.js" defer></script>
  @endif

  @if ($pathname == 'inventarios/crearEntrada')
  <script src="./js/main.js" defer></script>
  <script src="./js/productosEntradas/index.js" defer></script>
  <script src="./js/proveedores/proveedorController.js" defer></script>
  <script src="./js/productos/productoController.js" defer></script>
  <script src="./js/productosEntradas/entradaController.js" defer></script>
  @endif

  @if ($pathname == 'pos')
      <script src="./js/main.js" defer></script>
      <script src="./js/pos/index.js" defer></script>
      <script src="./js/clientes/clienteController.js" defer></script>
      <script src="./js/productos/productoController.js" defer></script>
      <script src="./js/facturas/facturaController.js" defer></script>
  @endif
  {{-- <script src="./js/productosEntradas/index.js"></script> --}}


  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js" defer></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
  <script src="assets/vendor/chart.js/chart.umd.js" defer></script>
  <script src="assets/vendor/echarts/echarts.min.js" defer></script>
  <script src="assets/vendor/quill/quill.min.js" defer></script>
  <script src="assets/vendor/tinymce/tinymce.min.js" defer></script>
  <script src="assets/vendor/php-email-form/validate.js" defer></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js" defer></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js" defer></script>

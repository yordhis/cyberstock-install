  <!-- ======= Footer ======= -->

      <footer class="footer ">
          <div class="copyright text-black">
              &copy; Copyright <strong><span class="text-primary">Cyber Staff C.A 2023</span></strong>. Todos los derechos
              reservados
          </div>
          <div class="credits">
               <a class="text-primary" href="https://cyberstaffstore.com/" target="_blank">Diseñado por el Equipo de desarrollo Cyber Staff</a>
          </div>
      </footer><!-- End Footer -->




  <script src="{{ asset('/assets/js/main.js') }}" defer></script>
  <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
  <script src="{{ asset('/js/partials/alert.js') }}" defer></script>
  <script src="{{ asset('/js/partials/preload.js') }}" defer></script>
  

  <script> 
  try {
        const dataTable = new simpleDatatables.DataTable("#myTable", {
            searchable: true,
            fixedHeight: false,
            labels: {
                placeholder: "Buscar...",
                searchTitle: "Buecar",
                pageTitle: "Pagina {page}",
                perPage: "Entradas por página",
                noRows: "No hay resultados",
                info: "Demostración {start} a {end} de {rows} entradas",
                noResults: "Ningún resultado coincide con su consulta de búsqueda",
            },
        })
  } catch (error) {
        console.log(error)
  }
</script>
  
  

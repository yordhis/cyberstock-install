<!doctype html>
<html lang="es">

<head>
  @include('partials.headpos')
</head>

<body>

  @include('partials.headerpos')
  
  <main id="mainPos" class="mainPos ">

      @yield('content')
   
  </main><!-- End #main -->

  {{-- <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> --}}


 @include('partials.footerTwo')

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
  @include('partials.head')
</head>

<body>
  @include('partials.header')
  @include('partials.sidebar')
  
  <main id="main" class="main">

    @include('partials.pagetitle')

    <div class="container">
      @yield('content')
    </div>
  </main><!-- End #main -->

  {{-- <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> --}}

 @include('partials.footerTwo')

</body>

</html>
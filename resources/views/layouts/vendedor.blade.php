<!DOCTYPE html>
<html lang="es">

<head>
  @laravelPWA
  @include('partials.headvendedor')
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

 @include('partials.footerTwo')

</body>

</html>
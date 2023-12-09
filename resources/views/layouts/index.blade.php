<!DOCTYPE html>
<html lang="es">

<head>
    @include('partials.headindex')
</head>

<body style="background-image: url('assets/img/fondo.png')">

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main><!-- End #main -->

    {{-- <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> --}}
    @include('partials.footer')


</body>

</html>

@php
    $url = explode('/',$_SERVER['REQUEST_URI']);
    $categoria = strtoupper($url[1]);
    $categoria = explode('?', $categoria)[0] ?? $categoria;

    if (isset($url[2])) {
        $subcategoria = $url[2];
        $subcategoria = explode('?', $subcategoria)[0] ?? $subcategoria;
    }
@endphp

<div class="pagetitle">
    <h1 class="text-primary">{{ $categoria }}</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Admin</a></li>
        <li class="breadcrumb-item active">{{ ucfirst(strtolower($categoria)) }}</li>
        @isset($subcategoria)
            <li class="breadcrumb-item active">{{ ucfirst(strtolower($subcategoria)) }}</li>
        @endisset
      </ol>
    </nav>
</div>
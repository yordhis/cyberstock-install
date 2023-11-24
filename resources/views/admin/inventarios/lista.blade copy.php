@extends('layouts.app')

@section('title', 'Inventario')

@section('content')

    @isset($respuesta['activo'])
        @include('partials.alert')
    @endisset

    <div id="alert"></div>

    <section class="section">
        <div class="row">



            <div class="col-sm-12">
                <h2> Inventario </h2>
            </div>

            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body table-responsive">

                        <!-- Table with stripped rows -->

                        <table class="table datatable" >
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Código</th>
                                    <th scope="col" style="width: 350px;">Descripción</th>
                                    {{-- <th scope="col">fecha de entrada</th> --}}
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Costo</th>
                                    <th scope="col">PVP</th>
                                    <th scope="col">PVP USD</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Categoria</th>
                                 
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $contador = 1; @endphp
                                @foreach ($inventarios as $inventario)
                                    <tr >
                                        <th scope="row">{{ $contador }}</th>
                                        <td>{{ $inventario->codigo }}</td>
                                        <td>{{ $inventario->descripcion }}</td>
                                        {{-- <td>{{ date_format(date_create($inventario->fecha_entrada), 'd-m-Y') }}</td> --}}
                                        <td>{{ $inventario->cantidad }}</td>
                                        <td>{{ number_format(doubleval($inventario->costo), 2, ',', '.') }}</td>
                                        <td>{{ number_format($inventario->pvp * $utilidades[0]->tasa, 2, ',', '.') }}</td>
                                        <td>{{ number_format($inventario->pvp, 2, ',', '.') }}</td>
                                        <td>{{ $inventario->id_marca->nombre }}</td>
                                        <td>{{ $inventario->id_categoria->nombre }}</td>
                                        


                                        <td>
                                          
                                            @include('admin.inventarios.partials.modalVer')

                                            @include('admin.inventarios.partials.modalEliminar')

                                            {{-- @include('admin.inventarios.partials.modalEditar') --}}

                                        </td>
                                    </tr>
                                    @php $contador++; @endphp
                                @endforeach

                            </tbody>
                        </table>

                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>



        </div>
    </section>

  



@endsection

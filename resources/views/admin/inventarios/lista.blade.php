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

                        <table class="table" >
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
                            <tbody id="lista">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                 
                                        <button type="button" class="btn btn-success"><i class="bi bi-eye"></i></button>
                                        <button type="button" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                        <button type="button" class="btn btn-warning"><i class="bi bi-pencil"></i></button>
     
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>



        </div>
    </section>

  



@endsection

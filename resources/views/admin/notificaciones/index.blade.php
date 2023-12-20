@extends('layouts.app')

@section('title', 'Lista de Notificaciones')

@section('content')
    <div id="alert"></div>
    <section class="section">
        <div class="row">

            <div class="col-sm-8">
                <h2>Lista de Notificaciones</h2>
                <p>
                    En esta lista podrá visualizar todos los productos que tengan una existencia de menos de 5 unidades.
                </p>
            </div>

            <div class="col-sm-4 text-end">
                <form action="{{ route('admin.notificaciones.destroyAll') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger m-1" >
                        <i class="bi bi-trash"> Borrar todas las notificaciones</i>
                    </button>
                </form>

                <form action="{{ route('admin.notificaciones.marcaComoLeidoAll') }}" method="post">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success m-1" >
                        <i class="bi bi-check"> Marcar todo como leido</i>
                    </button>
                </form>
            </div>
          

            <div class="col-lg-12 mt-4 ">

                <div class="card">
                    <div class="card-body table-responsive">
                    
                        <!-- Table with stripped rows -->
                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Concepto</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($notificaciones as $item)
                                        <tr>
                                            <td scope="row">{{ $item->id }}</td>
                                            <td>{{ $item->concepto_notificacion }}</td>
                                            <td>{{ $item->codigo }}</td>
                                            <td>{{ $item->descripcion }}</td>
                                            <td>{{ $item->cantidad }}</td>
                                            <td>
                                                @if ($item->estatus)
                                                    <form action="{{ route('admin.notificaciones.update', $item->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="estatus" value="0">
                                                        <input type="hidden" name="accion" value="marcarComoLeidoSingular">
                                                        <button class="btn btn-success" >Visto</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.notificaciones.update', $item->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="estatus" value="1">
                                                        <input type="hidden" name="accion" value="marcarComoLeidoSingular">
                                                        <button class="btn btn-danger" >No visto</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.notificaciones.destroy', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger m-1" >
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>

                                                <a href="{{ route('admin.inventarios.crearEntrada') }}"
                                                    class="btn btn-primary m-1"
                                                >
                                                    <i class="bi bi-paypal "></i> 
                                                </a>
                                    
                                            </td>
                                        </tr>
                                     
                                    @endforeach
                                    
                                </tbody>
                            </table>
                     
                        <!-- End Table with stripped rows -->
                            {{ $notificaciones->links() }}
                            {{ "Total de notificaciones: " . $notificaciones->total() }}
                    </div>
                </div>

            </div>



        </div>
    </section>

    
  
 

@endsection

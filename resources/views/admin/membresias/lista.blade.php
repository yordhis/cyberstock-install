@extends('layouts.app')

@section('title', 'Licencia')

@section('content')

    @include('partials.alertSession')
    <div id="alert"></div>
    <section class="section profile">
        <div class="row">
            <div class="col-sm-12">
                <a href="{{ route('admin.membresias.create') }}" class="btn btn-primary fs-5 mb-3" >
                    <i class="bi bi-filetype-key "></i>
                    Crear Membresía
                </a>
            </div>
            
                <div class="col-12">
                    @if (count($membresia))
                        <div class="card">
                            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                                <h2>{{ $membresia[1] }}</h2>
                                <h4>Rif:{{ $membresia[2] }}</h4>
                                <div class="social-links mt-2">
                                    
                                    <h5 class="fs-6">Paquete: {{ $membresia[0] }}</h5>
                                    <h5 class="fs-6">Fecha de incio de contrato: {{ $membresia[3] }}</h5>
                                    <h5 class="fs-6">Fecha de vencimiento de contrato: {{ $membresia[4] }}</h5>
                                    @if (Auth::user()->rol == 1) 
                                        <a href="{{route('admin.membresias.edit', $membresia[5] )}}" class="facebook" >
                                            <i class="bi bi-pencil fs-3 text-warning"></i>
                                        </a>
                                    
                                        @include('admin.membresias.partials.modal')
                                    @endif

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-danger">
                            no hay membresia asignada
                        </div>
                    @endif
                </div>
         

        </div>
    </section>

@endsection

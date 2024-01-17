@extends('layouts.app')

@section('title', 'Crear Usuario')


@section('content')
    <div class="container">


        @isset($respuesta)
            @include('partials.alert')
        @endisset
        <div id="alert"></div>

        <section class="section register d-flex flex-column align-items-center justify-content-center ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class=" col-sm-8 d-flex flex-column align-items-center justify-content-center">


                        <div class="card ">

                            <div class="card-body">

                                <div class=" pb-2">
                                    <h5 class="card-title text-center pb-0 fs-2">Crear Usuario</h5>
                                    <p class="text-center text-danger small">Rellene todos los campos</p>
                                </div>

                                <form action="{{ route('admin.users.store') }}" method="post" 
                                enctype="multipart/form-data"
                                    class="row g-3 needs-validation shadow-lg" novalidate>
                                    @csrf
                                    @method('post')
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Email de Acceso</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend">@</span>
                                            <input type="text" name="email" class="form-control" id="yourUsername"
                                                placeholder="Ingrese su email de acceso"
                                                value="{{ $request->email ?? '' }}" required>
                                            <div class="invalid-feedback">Por favor ingrese su nombre de usuario! </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Nombre de usuario</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend">@</span>
                                            <input type="text" name="nombre" class="form-control" id="yourUsername"
                                                placeholder="Ingrese su nombre de usuario"
                                                value="{{ $request->nombre ?? '' }}" required>
                                            <div class="invalid-feedback">Por favor ingrese su nombre de usuario! </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Contrseña</label>
                                        <input type="password" name="password" class="form-control" id="yourPassword"
                                            placeholder="Ingrese su contraseña" value="{{ $request->clave ?? '' }}"
                                            required>
                                        <div class="invalid-feedback">Por favor ingrese su contraseña!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourName" class="form-label">Rol del usuario</label>
                                        <select name="rol" id="rol" class="form-select" required>
                                            <option value="">Seleccione un rol</option>
                                            @foreach ($roles as $rol)
                                                @isset($request->rol)
                                                    @if ($request->rol == $rol->id)
                                                        <option value="{{ $rol->id }}" selected>{{ $rol->nombre }}
                                                        </option>
                                                    @endif
                                                @endisset

                                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                            @endforeach


                                        </select>
                                        <div class="invalid-feedback">Por favor, Seleccione el Rol del usuario!</div>
                                    </div>


                                    {{-- <div class="col-12">
                                        <label for="yourName" class="form-label">Seleccione los permisos del usuario</label><br>
                                        <small class="text-muted">Estos permisos le permite al usuario acceder a las secciones del sistema.</small><br>

                                        @foreach ($permisos as $permiso)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" name="per_{{ $permiso->id }}"
                                                value="{{ $permiso->id }}" 
                                                type="checkbox" id="inlineCheckbox1"
                                                {{ $permiso->activo ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="inlineCheckbox1">{{ $permiso->nombre }}</label>
                                            </div>
                                        @endforeach



                                        <div class="invalid-feedback">Please, enter your name!</div>
                                    </div> --}}

                                    <div class="col-12">
                                        <label for="foto" class="form-label">Subir Foto (Opcional)</label>
                                        <input type="file" name="file" class="form-control " id="foto">
                                        <div class="invalid-feedback">Ingrese una imagen valida</div>
                                    </div>




                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Crear usuario</button>
                                    </div>

                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>
    @endsection

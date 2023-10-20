@extends('layouts.app')

@section('title', 'Editar Estudiante')


@section('content')

  @isset($respuesta)
    @include('partials.alert')  
  @endisset

  <div class="container">

    <section class="section register d-flex flex-column align-items-center justify-content-center ">
      <div class="container">
        <div class="row justify-content-center">
          <div class=" col-sm-10 d-flex flex-column align-items-center justify-content-center">

            <div class="card ">

              <div class="card-body">

                <div class=" pb-2">
                  <h5 class="card-title text-center pb-0 fs-2">Actualizar Estudiante</h5>
                  <p class="text-center text-danger small">Rellene todos los campos </p>
                </div>

                <form action="/estudiantes/{{$estudiante->id}}" method="post" class="row g-3 needs-validation" target="_self" 
                  enctype="multipart/form-data"
                  novalidate>
                 @csrf
                 @method('put')  
                 
                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Nombre y apellido</label>
                        <div class="input-group has-validation">
                          <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">@</span>
                          <input type="text" name="nombre" class="form-control" id="yourUsername" 
                          placeholder="Ingrese su nombres y apellidos" value="{{$estudiante->nombre}}"
                          required>
                          <div class="invalid-feedback">Por favor, ingrese nombre! </div>
                        </div>
                      </div>

                      
                      <div class="col-4">
                        <label for="validationCustom04" class="form-label">Nacionalidad</label>
                        <select name="nacionalidad"  class="form-select" id="validationCustom04" required>
                          <option selected disabled value="">Seleccione Nacionalidad</option>
                         
                          @if (isset($estudiante->nacionalidad))
                            @if($estudiante->nacionalidad == "V")
                              <option value="V" selected>V</option>
                              @else
                              <option value="E" selected>E</option>
                            @endif
                          @endif
                          <option value="V">V</option>
                          <option value="E">E</option>
                        </select>
                        <div class="invalid-feedback">
                         Por favor, ingresar nacionalidad!
                        </div>
                      </div>

                      {{-- Cedula --}}
                      <div class="col-4">
                        <label for="yourPassword" class="form-label">Cédula de identidad</label>
                        <input type="text" name="cedula" class="form-control bg-secondary-light" id="yourUsername" 
                        placeholder="Ingrese número de cédula" value="{{$estudiante->cedula}}"
                      
                        required>
                        <div class="invalid-feedback">Por favor, Ingrese número de cédula!</div>
                      </div>

                      <div class="col-4">
                        <label for="yourPassword" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" id="yourUsername" 
                        placeholder="Ingrese número de teléfono"
                        value="{{$estudiante->telefono}}"
                        required>
                        <div class="invalid-feedback">Por favor, Ingrese número de teléfono!</div>
                      </div>

                      <div class="col-4">
                        <label for="yourPassword" class="form-label">E-mail</label>
                        <input type="email" name="correo" class="form-control" id="yourUsername" 
                        placeholder="Ingrese dirección de correo."
                        value="{{$estudiante->correo}}"
                        >
                        <div class="invalid-feedback">Por favor, Ingrese dirección de correo!</div>
                      </div>

                      <div class="col-4">
                        <label for="yourPassword" class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="nacimiento" class="form-control" id="yourUsername" 
                        
                        value="{{$estudiante->nacimiento}}"
                        >
                        <div class="invalid-feedback">Por favor, ingrese fecha de nacimiento!</div>
                      </div>

                      <div class="col-4">
                        <label for="yourPassword" class="form-label">Edad</label>
                        <input type="number" name="edad" class="form-control" id="yourUsername" 
                        placeholder="Ingrese edad."
                        value="{{$estudiante->edad}}"
                        >
                        <div class="invalid-feedback">Por favor, Ingrese edad!</div>
                      </div>

                      <div class="col-12">
                        <label for="yourPassword" class="form-label">Dirección de habitación</label>
                        <input type="text" name="direccion" class="form-control" id="yourUsername" 
                        placeholder="Ingrese dirección de domicilio."
                        value="{{$estudiante->direccion}}"
                        >
                        <div class="invalid-feedback">Por favor, Ingrese edad!</div>
                      </div>

                      <div class="col-8">
                        <label for="file" class="form-label">Subir Foto (Opcional)</label>
                        <input type="file" name="file" class="form-control " id="file"> 
                        
                      </div>

                      <div class="col-4 card">
                        <img src="{{ $estudiante->foto }}" class="img-fluid rounded" alt="">                        
                      </div>

                        {{-- INICIO DE DATOS DEL REPRESENTANTE --}}
                        <div id="" class="row">

                          <div class="col-lg-12">

                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title">Representantes</h5>
                                @if (!count($representantes))
                                   
                                  <a href="" class="" id="btnAddRepresentante" target="_self" >
                                    <i class="bi bi-plus-circle fs-4"> Añadir representante</i>
                                  </a>
                                  <div id="addRepresentante"></div>
                                @endif

                                {{-- INICIO DEL CICLO DE REPRESENTANTES --}}
                                @foreach ($representantes as $representante)
                                  @if ($representante->data->estatus)
        
                                  <!-- Accordion without outline borders -->
                                  <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                      <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                          {{ $representante->data->nombre . " - " . $representante->data->telefono }}
                                        </button>
                                      </h2>

                                      <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    
                                        <div class="col-12">
                                          <hr>
                                            <label for="yourName" class="form-label">Nombre del representante</label>
                                            <input type="text" name="rep_nombre" class="form-control" id="yourName"
                                                placeholder="Ingrese Nombre del representante."
                                                value="{{$representante->data->nombre}}"
                                                >
                                            <div class="invalid-feedback">Por favor, Nombre del representante!</div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Cédula</label>
                                            <input type="text" name="rep_cedula" class="form-control"
                                                id="yourUsername" placeholder="Ingrese la cédula del representante."
                                                value="{{$representante->data->cedula}}"
                                                >
                                            <div class="invalid-feedback">Por favor, Ingrese la cédula del representante!
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Teléfono </label>
                                            <input type="text" name="rep_telefono" class="form-control"
                                                id="yourUsername" placeholder="Ingrese teléfono del representante."
                                                value="{{$representante->data->telefono}}"
                                                >
                                            <div class="invalid-feedback">
                                              Por favor, Ingrese teléfono del representante!
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Edad</label>
                                            <input type="number" name="rep_edad" class="form-control" id="yourUsername"
                                                placeholder="Ingrese edad." 
                                                value="{{$representante->data->edad}}"
                                                >
                                            <div class="invalid-feedback">Por favor, Ingrese edad!</div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Ocupación</label>
                                            <input type="text" name="rep_ocupacion" class="form-control"
                                                id="yourUsername" placeholder="Ingrese ocupación o oficio." 
                                                value="{{$representante->data->ocupacion}}"
                                                >
                                            <div class="invalid-feedback">Por favor, ocupación o oficio!</div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Dirección del
                                                representante</label>
                                            <input type="text" name="rep_direccion" class="form-control"
                                                id="yourUsername" placeholder="Ingrese dirección del representante."
                                                value="{{$representante->data->direccion}}"
                                                >
                                            <div class="invalid-feedback">Por favor, Ingrese dirección del representante!
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Correo</label>
                                            <input type="text" name="rep_correo" class="form-control"
                                                id="yourUsername" placeholder="Ingrese correo." 
                                                value="{{$representante->data->correo}}"
                                                >
                                            <div class="invalid-feedback">Por favor, Ingrese correo del representante!
                                            </div>
                                        </div>

                                      </div>
                                    </div>
  
                                  </div><!-- End Accordion without outline borders -->

                                  @endif
                                  
                                @endforeach {{-- CIERRE DEL CICLO DE REPRESENTANTES --}}
                  
                              </div>
                            </div>
                  
                          </div>
            
                        </div> {{-- FIN DE DATOS DEL REPRESENTANTE --}}
                  
                        {{-- INICIO DE DIFICULTADES DEL ESTUDIANTE --}}
                        <div id="" class="row">

                          <div class="col-lg-12">

                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title">Dificultades de aprendizaje</h5>

                                
                                    
                                  <!-- Accordion without outline borders -->
                                  <div class="accordion accordion-flush" id="accordionFlushExampleTwo">
                                    <div class="accordion-item">
                                      <h2 class="accordion-header" id="flush-headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                          Ver dificultades
                                        </button>
                                      </h2>

                                      <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExampleTwo">
                                    
                                        
                                        @php $contador = 1; @endphp
                                        <div id="" class=" row">
                                          {{-- INICIO DEL CICLO DE LISTA DE DIFICULTADES --}}
                                          @foreach ($listDificultades as $item)
                                          
                                              @if ( $item->estatus )
                                                <div class="col-4">
                                                  <label for="yourUsername" class="form-label">{{$item->dificultad}}</label>
                                                  <input type="checkbox" name="dif_{{$contador}}" value="{{$item->dificultad}}"
                                                      class="form-checkbox" id="yourUsername"
                                                      checked
                                                      >
                                                </div>
                                               
                                              @else
                                                <div class="col-4">
                                                  <label for="yourUsername" class="form-label">{{$item->dificultad}}</label>
                                                  <input type="checkbox" name="dif_{{$contador}}" value="{{$item->dificultad}}"
                                                      class="form-checkbox" id="yourUsername"
                                                      
                                                      >
                                                </div>
                                                
                                              @endif

                                                
                                             
                                           
                                              
                                            @php $contador++; @endphp
                                          @endforeach {{-- CIERRE DEL CICLO DE LISTA DIFICULTADES DE APRENDIZAJE--}}
                                        </div> {{-- CIERRE DE DATOS DE DIFICULTAD DE APRENDIZAJE --}}
                                      </div>
                                    </div>
  
                                  </div><!-- End Accordion without outline borders -->
                                  
                  
                              </div>
                            </div>
                  
                          </div>
            
                        </div> {{-- FIN DE DIFICULTADES DEL ESTUDIANTE --}}
                  

                  
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Actualizar datos</button>
                  </div>
                  
                </form>

              </div>

            </div>

            {{-- <div class="credits"> --}}
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
               {{-- <a href="https://bootstrapmade.com/">BootstrapMade</a> --}}
            {{-- </div> --}}

          </div>
        </div>
      </div>

    </section>
  </div>
@endsection



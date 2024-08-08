{{-- Tarjeta del vendedor --}}
<div class="card" style="height: auto; width: 100%;">
    <div class="card-body">
        {{-- <img src="{{ Auth::user()->foto }}" alt="" class="img rounded w-50"> --}}
        <div class="{{ $pathname == "pos" || $pathname == "inventarios/crearEntrada" ? 'd-none' : ''}}">

            <i>Asigne un vendedor</i>
            <h5 class="card-text text-danger">
                <strong>Vendedor: </strong>  
            </h5>
            <select name="vendedor" id="vendedor" class="form-control">

                @foreach ($usuarios as $usuario)
                    @if($usuario->id == Auth::user()->id)
                        <option value="{{ $usuario->id }}" selected>{{ $usuario->nombre}}</option>

                    @else

                        <option value="{{ $usuario->id }}">{{ $usuario->nombre}}</option>
                    @endif

                @endforeach
            </select>
            <hr>
        </div>
        
        <p class="card-text mt-1" style="">
            
            <span>
                {!! $pathname == "pos" || $pathname == "inventarios/crearEntrada" ? "<strong class='text-danger'>Vendedor: </strong>" : "<strong>Usuario: </strong>" !!}
                <span id="idUsuario" class="text-dark">
                    N°: {{ Auth::user()->id }} -
                </span> 
                {{ Auth::user()->nombre }}
            </span>
            <br>
        </p>

        <input type="hidden" name="usuario" id="usuario" value="{{ Auth::user()->email }}">
        <input type="hidden" name="usuario" id="nombreUsuario" value="{{ Auth::user()->nombre }}">
    </div>
</div>
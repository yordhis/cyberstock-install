{{-- Tarjeta del vendedor --}}
<div class="card" style="height: auto; width: 100%;">
    <div class="card-body">
        <img src="{{ Auth::user()->foto }}" alt="" class="img rounded w-50">
        <h5 class="card-text text-danger">Vendedor: 
            <span id="idUsuario" class="text-dark fs-5">
                {{ Auth::user()->id }}
            </span>
        </h5>

        <p class="card-text mt-0" style="">
            <b>Usuario:</b> 
            <span>
                {{ Auth::user()->nombre }}
            </span>
            <br>
        </p>

        <input type="hidden" name="usuario" id="usuario" value="{{ Auth::user()->email }}">
        <input type="hidden" name="usuario" id="nombreUsuario" value="{{ Auth::user()->nombre }}">
    </div>
</div>
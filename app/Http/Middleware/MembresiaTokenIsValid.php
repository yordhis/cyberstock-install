<?php

namespace App\Http\Middleware;

use App\Models\Membresia;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MembresiaTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return redirect('logout');

        $token = count(Membresia::all()) ? Membresia::all()[0] : [];
        $membresia = [];
        if(count(Membresia::all())){
            $membresia = explode(",", Crypt::decrypt($token->token, false));
            array_push($membresia, $token->id);
        }
// Carbon::create()

        if(count($membresia) == 0){
            if($request->user()->rol == 1){
                return $next($request);
            }else{
                session([
                    "mensaje" => "Por favor pongase en contacto con el proveedor del sistema para adquirir su licencia. contactos: 0414-3534569 / 0412-6772002",
                    "estatus" => 401
                ]);
                return redirect('logout');
            }
        }else{
            /** si tiene membresia */
            /** Obtenemos la hora actual */
            $fechaActual = Carbon::now('America/Caracas');
           
            /** configurar la fecha de vencimiento de membresia */
            $fvm = explode("-", $membresia[4]);

            $fvmd = Carbon::create($fvm[0],$fvm[1],$fvm[2], 0, 0, 0, 'America/Caracas');

            $diferenciaDeDias = ceil($fechaActual->diffInDays($fvmd));

            /** validamos si la fecha actual es mayor a la del vencimiento */
            if($fechaActual->greaterThan($fvmd)){
                session([
                    "mensaje" => "Su licencia está vencida. fecha de vencimiento: {$fvm[2]}-{$fvm[1]}-{$fvm[0]}. contactos: 0414-3534569 / 0412-6772002",
                    "estatus" => 401
                ]);
                return redirect('logout');

            }elseif ( $diferenciaDeDias <= 5 ) {
                session([
                    "mensaje" => "Su licencia está por expirar, le quedan aproximadamente {$diferenciaDeDias} días para renovar; Su fecha de vencimiento es: {$fvm[2]}-{$fvm[1]}-{$fvm[0]}. contactos: 0414-3534569 / 0412-6772002",
                    "estatus" => 401
                ]);
            }

        }

        return $next($request);
    }
}

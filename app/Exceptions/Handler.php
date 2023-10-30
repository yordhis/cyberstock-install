<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

//  Modelos
use App\Models\{
    DataDev,
    Helpers
};

// Excepciones
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Exceptions\InvalidOrderException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Facade\Ignition\Exceptions\ViewException;
use Throwable;

class Handler extends ExceptionHandler
{


    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];


    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
      
      
        $this->renderable(function (QueryException $e, $request) {
            // dd($e);
            $errorInfo = Helpers::getMensajeError($e, "No se puede establecer una conexión ya que el equipo de destino denegó expresamente dicha conexión,");
            return response()->view('errors.500', compact("errorInfo"), 500);
        });


        $this->renderable(function (NotFoundHttpException $e, $request) {
            // dd($e);
            $errorInfo = Helpers::getMensajeError($e, "Error de consula,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        });

        $this->renderable(function (RouteNotFoundException $e, $request) {
            // dd($e);
            $errorInfo = Helpers::getMensajeError($e, "La ruta solicitada no esta definida,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        });

        $this->renderable(function (ViewException $e, $request) {
            // dd($e);
            $errorInfo = Helpers::getMensajeError($e, "Error de datos de la Vista,");
            return response()->view('errors.404', compact("errorInfo"), Response::HTTP_NOT_FOUND);
        });

        /** Se capturan los errores no definidos */
        $this->renderable(function (Throwable $e, $request) {
            dd();
            $errorInfo = Helpers::getMensajeError($e, "Error de Servidor,");
            return response()->view('errors.404', compact('errorInfo', 'pathname'), 404);
        });
    }

    
}

<?php

namespace App\Models;
use App\Models\{
    User,
    RolPermiso,
    Permiso,
    Role,
    
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helpers extends Model
{
    use HasFactory;

    public static $productos;
    public static $fechaCuota;


    public static function registrarMovimientoDeUsuario($request, $codigo="", $observacion=""){
        Monitore::create([
            'usuario' => Auth::user()->email,  // email
            'nombre' => Auth::user()->nombre, 
            'transaccion' => $request->path(), 
            'codigo' =>  $codigo,
            'observacion' => $observacion,
            'ubicacion' => $request->url(), 
            'dispositivo' => $request->ip(), 
            'fecha' => Carbon::now()
        ]);
        return true;
    }

    public static function setNameElementId($datos, $campos, $tables){
       
        $tables = explode(",", $tables);
        foreach ($datos as $key => $dato) {
         
            foreach ($tables as $table) {
                $keyDato = "id_" . substr($table, 0, -1);
                $dato->{$keyDato} = DB::select("SELECT {$campos} FROM {$table} WHERE id = ?", [$dato->{$keyDato}]);
                if($dato->{$keyDato}) $dato->{$keyDato} = $dato->{$keyDato}[0];
                else $dato->{$keyDato} = "No asignado";
            }
        }
        return $datos;
    }


   
    public static function setFechasHorasNormalizadas($datos)
    {
        $fechaInscripcion = Carbon::parse($datos->fecha);
        $dtInit = Carbon::parse($datos->grupo['fecha_inicio']);
        $dtEnd = Carbon::parse($datos->grupo['fecha_fin']);
        $htInit = Carbon::parse($datos->grupo['hora_inicio']);
        $htEnd = Carbon::parse($datos->grupo['hora_fin']);

        // Normalizando fechas y horas
        $datos->fecha_init = $dtInit->format('d-m-Y');
        $datos->fecha_end = $dtEnd->format('d-m-Y');
        $datos->hora_init = $htInit->format('h:ia');
        $datos->hora_end = $htEnd->format('h:ia');
        $datos->fecha = $fechaInscripcion->format('d-m-Y');

        return $datos;
    }

    public static function getUsuarios()
    {
        $usuarios = User::where('rol', '>', 1)->get();
        foreach ($usuarios as $key => $usuario) {
            $usuarios[$key] = self::getUsuario($usuario->id);
        }
        return $usuarios;
    }

    public static function getUsuario($id)
    {
        $usuario = User::where("id", $id)->get()[0];
        if ($usuario) {
            $usuario->permisos = self::getPermisosUsuario(RolPermiso::where("id_rol", $usuario->rol)->get());
            $usuario->rol = Role::where("id", $usuario->rol)->get()[0];
        }
        return $usuario;
    }

    public static function getPermisosUsuario($permisos)
    {
        $permisosObject = [];
        foreach ($permisos as $permiso) {
            $permisosObject[$permiso->id_permiso] = Permiso::where('id', $permiso->id_permiso)->get()[0];
        }
        return $permisosObject;
    }


    /** Esta funcion retorna el siguiente codigo de la tabla solicitada */
    public static function getCodigo($table)
    {
        $codigo = "00000000";
        $ultimoCodigo = DB::table($table)->max('codigo');
        if ($ultimoCodigo) {
            $nuevoCodigo = intval($ultimoCodigo) + 1;
            $codigo = Str::substr("00000000", Str::length($nuevoCodigo), Str::length($codigo)) . $nuevoCodigo;
        }else{ 
            $codigo = Str::substr($codigo, 1, Str::length($codigo)) . "1";
        }
        return $codigo;
    }

    public static function getMensajeError($e, $mensaje)
    {
        $errorInfo = $mensaje . config("const.ERR_CLIENT.{$e->getCode()}") ?? 'No hay mensaje de error';
        return $errorInfo;
    }

    /**
     * Esta funcion recibe la informacion del formulario y detecta cuales son los input que
     * contienen el prefijo @var dif_ y las convierte en un array.
     *
     * @param Request
     */
    public static function getArrayInputs($request, $prefijo = "dif")
    {
        $array = null;
        foreach ($request as $key => $value) {
            $text = substr($key, 0, 3);

            if ($text == $prefijo) : $array[] = $value;
                continue;
            endif;
        }

        return $array;
    }

    /**
     * Esta funcion retorna los checkbox activos de los elementos deseados
     * @param datos array
     * @param inputChecks array
     */

    public static function getCheckboxActivo($datos, $inputChecks)
    {
        foreach ($datos as $key => $dato) {
            $dato->activo = 0;
            foreach ($inputChecks as $check) {
                if ($dato->id == $check) $dato->activo = 1;
            }
        }
        return $datos;
    }

    /**
     * Esta funcion se encarga de guardar la imagen en el store en la direccion public/fotos
     * recibe los siguientes parametros
     * @param request  Estes es el elemento global de las peticiones y se accede a su metodo file y atributo file
     * @return url Retorna la direccion donde se almaceno la imagen
     */
    public static function setFile($request)
    {
        // Movemos la imagen a storage/app/public/fotos
        $imagen = $request->file('file')->store('public/fotos');

        // configuramos la url de /public a /storage
        $url = Storage::url($imagen);

        // Retorna la URL de la imagen
        return $url;
    }

    /**
     * Eliminamos la imagen o archivo anterior
     * @param url se solicita la url del archivo para removerlo de su ubicacion
     */
    public static function removeFile($url)
    {
        $paths = str_replace('storage', 'public', $url);
        if (Storage::delete($paths)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Seteamos la data relacional a los grupos y retornamos los datos
     *
     * @param array
     * Este recibe el arreglo donde se desea añadir la informacion de las relaciones.
     *
     * @param arrayKey
     * Este parametro recibe un array asociativo que el key hace referencia a la tabla de la base de datos
     * y el valor al key de relacion a la otra tabla de la DB.
     *
     * ejemplo: ["profesores" => "cedula_profesor"]
     * Aqui buscamos los datos de la tabla grupos
     * desde el cedula_profesor
     *
     */

    public static function addDatosDeRelacion($array, $arrayKey, $sqlExtra = "")
    {
        if (count($array)) {
            foreach ($array as $key => $value) {
                foreach ($arrayKey as $keyTable => $valueKey) {
                    $llave = explode("_", $valueKey);
                    // return DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]]);
                    $array[$key][$llave[1]] = count(DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]])) > 1
                        ? DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]])
                        : DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]])[0] ?? [];
                }
            }
        }

        return $array;
    }

    /**
     * @param Object ### Recibe un objeto ###
     *  Esta funcion se encarga de convertir un objecto en una Arreglo Asociativo y asigna
     *  una llave o posicion [0]->data
     *
     */
    public static function setConvertirObjetoParaArreglo($object)
    {
        return [get_object_vars($object)];
    }
    //
    
    /**
     * Validar si el dato existe
     */

    public static function datoExiste($data, $array = ["tabla" => ["campo", "sqlExtra", "key"]])
    {
        foreach ($array as $key => $value) {
            return $result = count(DB::select("select * from {$key} where {$value[0]} = :codigo {$value[1]}", [$data[$value[2]]]))
                ? DB::select("select * from {$key} where {$value[0]} = :codigo {$value[1]}", [$data[$value[2]]])[0]
                : false;
        }
    }

    /**
     * Esta funcion calcula el rango de las fecha y retorna la siguiente fecha
     * de la cuota a cobrar
     *
     */
    public static function getFechaCuota($plan)
    {
        $dt = Carbon::create(self::getFechaCuotaActual());
        $date = explode(" ", $dt->addDays($plan->plazo))[0];
        static::setFechaCuota($date);
        return $date;
    }

    public static function setFechaCuota($fecha)
    {
        static::$fechaCuota = $fecha;
    }

    public static function getFechaCuotaActual()
    {
        return self::$fechaCuota;
    }

    public static function auto_decimal_format($n, $def = 2)
    {
        $a = explode(".", $n);
        if (count($a) > 1) {
            $b = str_split($a[1]);
            $pos = 1;
            foreach ($b as $value) {
                if ($value != 0 && $pos >= $def) {
                    $c = number_format($n, $pos);
                    $c_len = strlen(substr(strrchr($c, "."), 1));
                    if ($c_len > $def) {
                        return rtrim($c, 0);
                    }
                    return $c; // or break
                }
                $pos++;
            }
        }
        return number_format($n, $def);
    }
} // end

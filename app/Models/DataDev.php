<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

class DataDev
{

    public $respuesta;
    public $notificaciones;
    public $usuario;
    public $dias;
    public $metodosPagos;
    public $estatus;
    public $menuSuperior;
    public $utilidades;
    public $tiposEntradas;
    public $tiposSalidas;
    public $datosDefault;
    /**
     * Constructor
     */
     public function __construct(){

        $this->datosDefault = [
            "FOTO_PORDEFECTO" => "/storage/fotos/default.jpg",
            "FOTO_PORDEFECTO_PRODUCTO" => "/storage/fotos/producto.jpg",
            "LOGO_PORDEFECTO" => "/storage/fotos/logo-default.png",
        ];
        
        $this->tiposEntradas = [
            "COMPRA", // 5
            "DEVOLUCION", // 6
            "INICIALIZACION" // 7
        ];
        
        $this->tiposSalidas = [
            "VENTA", // 1
            "CONSUMO", // 2
            "DAÑADO", // 3
            "CREDITO", // 4
        ];


        $this->menuSuperior = [
            "inventarios" => [
                "General" => "",
                "Lista de salidas" => "listaSalidas",
                "Lista de entradas" => "listaEntradas",
                "Crear Salida" => "crearSalida",
                "Crear Entrada" => "crearEntrada",
            ],
            "productos" => [
                "productos" => "index",
                "categorias" => "index",
                "marcas" => "index"
            ],
        ];

        $this->respuesta=[
            "mensaje" => "No Funcionó",
            "activo" => null,
            "estatus" => 404,
            "clases" => [
                "200" => "alert-success",
                "201" => "alert-success",
                "301" => "alert-warning",
                "401" => "alert-warning",
                "404" => "alert-danger",
            ],
            "icono" => [
                "200" => "bi bi-check-circle me-1",
                "201" => "bi bi-check-circle me-1",
                "301" => "bi bi-exclamation-triangle me-1",
                "401" => "bi bi-exclamation-octagon me-1",
                "404" => "bi bi-exclamation-octagon me-1"
            ]
        ];

        $this->metodosPagos = [
            [
                "metodo" => "TD",
                "activo" => false           
            ],
            [
                "metodo" => "EFECTIVO",
                "activo" => false           
            ],
            [
                "metodo" => "PAGO MOVIL",
                "activo" => false           
            ],
            [
                "metodo" => "DIVISAS",
                "activo" => false           
            ]
        ];

        $this->dias = [
            "Lunes",
            "Martes",
            "Miercoles",
            "Jueves",
            "Viernes",
            "Sabado",
            "Domingo"
        ];

        $this->estatus = [
            "0" => "Eliminado",
            "1" => "Activo",
            "2" => "Inactivo",
            "3" => "Completado",
        ];

     }

     

}

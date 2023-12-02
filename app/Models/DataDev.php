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

        $this->utilidades = Utilidade::all();
        if (count($this->utilidades)) {
            $this->utilidades[0]->iva = [ 
                "sumar" => $this->utilidades[0]->iva / 100 + 1,
                "restar" => $this->utilidades[0]->iva / 100,
                "iva" =>  $this->utilidades[0]->iva
            ];

            if ($this->utilidades[0]->pvp_1 > 0) {
                $this->utilidades[0]->pvp_1 = [ 
                    "sumar" => $this->utilidades[0]->pvp_1 / 100 + 1,
                    "restar" => $this->utilidades[0]->pvp_1 / 100
                ];
            }
            if ($this->utilidades[0]->pvp_2 > 0) {
                $this->utilidades[0]->pvp_2 = [ 
                    "sumar" => $this->utilidades[0]->pvp_2 / 100 + 1,
                    "restar" => $this->utilidades[0]->pvp_2 / 100
                ];
            }
            if ($this->utilidades[0]->pvp_3 > 0) {
                $this->utilidades[0]->pvp_3 = [ 
                    "sumar" => $this->utilidades[0]->pvp_3 / 100 + 1,
                    "restar" => $this->utilidades[0]->pvp_3 / 100
                ];
            }
        }

        $this->menuSuperior = [
            "inventarios" => [
                "General" => "",
                "Lista de salidas" => "listaSalidas",
                "Lista de entradas" => "listaEntradas",
                "Crear Salida" => "crearSalida",
                "Crear Entrada" => "crearEntrada",
            ],
            "productos" => [
                "General" => "",
                "Categorias" => "categorias",
                "Marcas" => "marcas"
            ],
            "pos" => [
                "Pos" => "",
                "Facturas" => "facturas",
                "Clientes" => "clientes",
                
            ]
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

        $this->notificaciones = [
            "total" => 5,
            "data"=>[
                ["descripcion"=>"Franklin Pago", "tipo"=>"Pago"],
                ["descripcion"=>"Franklin 2 Pago", "tipo"=>"Pago"],
                ["descripcion"=>"Franklin 3 Pago", "tipo"=>"Pago"],
                ["descripcion"=>"Franklin 4 Pago", "tipo"=>"Pago"],
                ["descripcion"=>"Franklin 5 Pago", "tipo"=>"Pago"]
            ]
        ];

        $this->estatus = [
            "0" => "Eliminado",
            "1" => "Activo",
            "2" => "Inactivo",
            "3" => "Completado",
        ];

        $this->usuario = [
            "nombre" => "admin",
            "rol" => "administrador",
        ];
     }


    public function getRespuesta(){
        return $this->respuesta;
    }

    public function getNotificaciones(){
        return $this->notificaciones;
    }

    public function getMetodosPagos(){
        return $this->metodosPagos;
    }

    public function getEstatusText(){
        return $this->estatus;
    }

}

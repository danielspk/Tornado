<?php
namespace app\modules\demo\controller;

class Demo extends \DMS\Tornado\Controller
{
    /**
     * Ejemplo de enrutamientos mediante anotaciones
     * @T_ROUTE /demo/anotacion
     * @T_ROUTE GET|POST /demo/otra/anotacion
     */
    public function index($param = null)
    {
        echo 'Hola Mundo Tornado';
    }

}

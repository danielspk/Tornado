<?php
namespace app\modules\demo\controller;

use \DMS\Tornado\Controller;

class Demo extends Controller
{
    /**
     * Ejemplo de enrutamientos mediante anotaciones
     *
     * @param mixed $param
     * @T_ROUTE /demo/anotacion
     * @T_ROUTE GET|POST /demo/otra/anotacion
     */
    public function index($param = null)
    {
        echo 'Hola Mundo Tornado'.$param;
    }

}

<?php
namespace app\modules\demo\controller;

class Demo extends \DMS\Tornado\Controller
{
    public function index($param = null)
    {
        //$pepep = 10 /0;
        echo ' Hola ' . $param . '<br>';

        echo '<form method="post"><input type="submit" value="enviar"/></form>';
    }
}

<?php
namespace app\modules\demo\controller;

class Demo extends \DMS\Tornado\Controller
{
	
    public function index($param = null)
    {
        echo 'Hola Mundo Tornado';
    }
	
}

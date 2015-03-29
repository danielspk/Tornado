<?php
namespace test\modules\demo\controller;

use \DMS\Tornado\Controller;

class Demo extends Controller
{
    /**
     * @T_ROUTE /demo/demo/index
     * @T_ROUTE GET|POST /demo/other/annotation
     */
    public function index() {}

}

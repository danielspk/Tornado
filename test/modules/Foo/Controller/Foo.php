<?php
namespace test\modules\Foo\Controller;

use \DMS\Tornado\Controller;

class Foo extends Controller
{
    /**
     * @T_ROUTE /demo/foo/index
     * @T_ROUTE GET|POST /foo/other/annotation
     */
    public function index(){}

}

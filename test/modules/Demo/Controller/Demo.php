<?php
namespace test\modules\Demo\Controller;

use \DMS\Tornado\Tornado;
use \DMS\Tornado\Controller;

class Demo extends Controller
{
    public function __construct(Tornado $pApp)
    {
        parent::__construct($pApp);
    }

    /**
     * @T_ROUTE /demo/demo/index
     * @T_ROUTE GET|POST /demo/other/annotation
     */
    public function index() {}

    public function testLoadController()
    {
        $this->loadController('foo|foo');
    }

    public function testLoadControllerError()
    {
        $this->loadController('foo2|foo2');
    }

    public function testLoadView()
    {
        $this->loadView('demo|demo');
    }

    public function testLoadViewError()
    {
        $this->loadView('demo2|demo2');
    }

    public function testLoadModel()
    {
        $this->loadModel('demo|demo');
    }

    public function testLoadModelError()
    {
        $this->loadModel('demo2|demo2');
    }
}

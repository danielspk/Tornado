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
        $this->loadController('Foo|Foo');
    }

    public function testLoadControllerError()
    {
        $this->loadController('Foo2|Foo2');
    }

    public function testLoadView()
    {
        $this->loadView('Demo|demo');
    }

    public function testLoadViewError()
    {
        $this->loadView('Demo2|demo2');
    }

    public function testLoadModel()
    {
        $this->loadModel('Demo|Demo');
    }

    public function testLoadModelError()
    {
        $this->loadModel('Demo2|Demo2');
    }
}

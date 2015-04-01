<?php

class ControllerTest extends PHPUnit_Framework_TestCase
{
    private $app;

    public function setUp()
    {
        $this->app = \DMS\Tornado\Tornado::getInstance();
        $this->app->config('tornado_hmvc_module_path', 'test/modules/');
    }

    public function testInstance()
    {
        $controller = new \test\modules\demo\controller\Demo($this->app);
        $this->assertInstanceOf('\test\modules\demo\controller\Demo', $controller);
    }

    public function testLoadController()
    {
        $controller = new \test\modules\demo\controller\Demo($this->app);
        $controller->testLoadController();
        $otherController = new \test\modules\foo\controller\Foo($this->app);
        $this->assertInstanceOf('\test\modules\foo\controller\Foo', $otherController);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadControllerError()
    {
        $controller = new \test\modules\demo\controller\Demo($this->app);
        $controller->testLoadControllerError();
    }

    public function testLoadView()
    {
        $controller = new \test\modules\demo\controller\Demo($this->app);
        $controller->testLoadView();
        $this->assertEquals(true, true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadViewError()
    {
        $controller = new \test\modules\demo\controller\Demo($this->app);
        $controller->testLoadViewError();
    }

    public function testLoadModel()
    {
        $controller = new \test\modules\demo\controller\Demo($this->app);
        $controller->testLoadModel();
        $this->assertEquals(true, true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadModelError()
    {
        $controller = new \test\modules\demo\controller\Demo($this->app);
        $controller->testLoadModelError();
    }
}
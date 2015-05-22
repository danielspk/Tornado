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
        $controller = new \test\modules\Demo\Controller\Demo($this->app);
        $this->assertInstanceOf('\test\modules\Demo\Controller\Demo', $controller);
    }

}
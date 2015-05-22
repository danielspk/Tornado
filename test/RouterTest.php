<?php

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $router = new \DMS\Tornado\Router();
        $this->assertInstanceOf('\DMS\Tornado\Router', $router);
    }
}
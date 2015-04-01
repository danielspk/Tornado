<?php

class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $route = new \DMS\Tornado\Route();
        $this->assertInstanceOf('\DMS\Tornado\Route', $route);
    }
}
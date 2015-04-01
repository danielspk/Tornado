<?php

class ServiceTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $service = new \DMS\Tornado\Service();
        $this->assertInstanceOf('\DMS\Tornado\Service', $service);
    }
}
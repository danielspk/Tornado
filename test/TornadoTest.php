<?php

class TornadoTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $tornado = \DMS\Tornado\Tornado::getInstance();
        $this->assertInstanceOf('\DMS\Tornado\Tornado', $tornado);
    }
}
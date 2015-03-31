<?php

class ErrorTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $error = new \DMS\Tornado\Error();
        $this->assertInstanceOf('\DMS\Tornado\Error', $error);
    }

    /**
     * @expectedException \ErrorException
     */
    public function testHandlerTrue()
    {
        $error = new \DMS\Tornado\Error();
        $error->setHandler(true);

        $var = 10 / 0;
    }

}
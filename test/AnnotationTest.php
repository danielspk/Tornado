<?php

class AnnotationTest extends PHPUnit_Framework_TestCase
{
    private $pathModules;
    private $pathFile;

    public function setUp()
    {
        $this->pathModules = 'test/modules';
        $this->pathFile = $this->pathModules . '/' . 'route_serialize.php';
    }

    public function testInstance()
    {
        $annotation = new \DMS\Tornado\Annotation();
        $this->assertInstanceOf('\DMS\Tornado\Annotation', $annotation);
    }

    public function testSerialize()
    {
        if (file_exists($this->pathFile))
            unlink($this->pathFile);

        $annotation = new \DMS\Tornado\Annotation();
        $annotation->findRoutes($this->pathModules, $this->pathModules);

        $this->assertFileExists($this->pathFile);

        $fileString = file_get_contents($this->pathFile);

        $this->assertContains('foo|foo|index', $fileString);

        $fileArray = unserialize($fileString);

        $this->assertInternalType('array', $fileArray);
    }

}
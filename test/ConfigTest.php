<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $config = new \DMS\Tornado\Config();
        $this->assertInstanceOf('\DMS\Tornado\Config', $config);
    }

    public function testDefaultValues()
    {
        $config = new \DMS\Tornado\Config([
            'tornado_environment_development' => true,
            'tornado_hmvc_use'                => false,
            'tornado_hmvc_module_path'        => '',
            'tornado_hmvc_serialize_path'     => ''
        ]);
        $this->assertEquals(true, $config['tornado_environment_development']);
        $this->assertEquals(false, $config['tornado_hmvc_use']);
        $this->assertEquals('', $config['tornado_hmvc_module_path']);
        $this->assertEquals('', $config['tornado_hmvc_serialize_path']);
    }

    public function testValues()
    {
        $config = new \DMS\Tornado\Config();
        $config['boolean'] = true;
        $config['string'] = 'true';
        $config['integer'] = 1;
        $config['double'] = 1.2;
        $config['object'] = new \stdClass();
        $this->assertEquals(null, $config['non_existent']);
        $this->assertEquals(true, $config['boolean']);
        $this->assertEquals('true', $config['string']);
        $this->assertEquals(1, $config['integer']);
        $this->assertEquals(1.2, $config['double']);
        $this->assertInstanceOf('\stdClass', $config['object']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testException()
    {
        $config = new \DMS\Tornado\Config();
        $config[] = 'foo';
    }

    public function testExists()
    {
        $config = new \DMS\Tornado\Config();
        $config['foo'] = 'foo';

        $this->assertEquals(true, isset($config['foo']));
        $this->assertEquals(false, empty($config['foo']));
        $this->assertEquals(false, isset($config['non_exits']));
        $this->assertEquals(true, empty($config['non_exists']));

        unset($config['foo']);

        $this->assertEquals(false, isset($config['foo']));
        $this->assertEquals(true, empty($config['foo']));
    }
}
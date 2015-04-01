<?php

class HookTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $hook = new \DMS\Tornado\Hook();
        $this->assertInstanceOf('\DMS\Tornado\Hook', $hook);
    }

    public function testRegisterCallOrder()
    {
        $hook = new \DMS\Tornado\Hook();

        $hook->register('hook', function(){
            return 'A';
        }, 1);

        $hook->register('hook', function(){
            return 'B';
        }, 0);

        $this->assertEquals('A', $hook->call('hook'));
    }

    public function testRegisterCallAbort()
    {
        $hook = new \DMS\Tornado\Hook();

        $hook->register('hook', function(){
            return false;
        }, 0);

        $hook->register('hook', function(){
            return 'A';
        }, 1);

        $this->assertFalse($hook->call('hook'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRegisterCallError()
    {
        $hook = new \DMS\Tornado\Hook();

        $hook->register('hook', function(){
            return true;
        }, null);

        $this->assertFalse($hook->call('hook2'));
    }

    public function testRegisterCallOk()
    {
        $hook = new \DMS\Tornado\Hook();

        $hook->register('hook', function(){

        }, null);

        $this->assertNull($hook->call('hook'));
    }

    public function testRegisterCallCount()
    {
        $app = \DMS\Tornado\Tornado::getInstance();
        $app->config('count', 0);

        $hook = new \DMS\Tornado\Hook();

        $hook->register('hook', function() use ($app) {
            $count = $app->config('count') + 1;
            $app->config('count', $count);
            return true;
        }, 0);

        $hook->register('hook', function() use ($app) {
            $count = $app->config('count') + 2;
            $app->config('count', $count);
            return true;
        }, 1);

        $hook->call('hook');
        $count = $count = $app->config('count');
        $this->assertEquals(3, $count);
    }
}
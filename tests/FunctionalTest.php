<?php

class FunctionalTest extends PHPUnit_Framework_TestCase
{

    private $container;

    protected function setUp()
    {
        $this->container = new Syringe\Container(__DIR__ . '/services.json');
    }

    public function testCorrectInstantiation()
    {
        $this->assertInstanceOf('DummyServices\ClassA', $this->container->get('class-a'));
    }

    public function testCorrectInstantiationWithArgs()
    {
        $this->assertInstanceOf('DummyServices\ClassB', $this->container->get('class-b'));
    }

    /**
     * @expectedException Syringe\CircularDependencyException
     */
    public function testDetectCircularDependencies()
    {
        $this->container->get('class-c');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotExistingService()
    {
        $this->container->get('class-x');
    }

}

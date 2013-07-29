<?php
namespace tests\Application;

use carlescliment\moco\Application\Moco;


class ApplicationTest extends \PHPUnit_Framework_TestCase
{

	private $container;
	private $containerLocator;
	private $app;

	public function setUp()
	{
		$this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$this->app = new Moco;
		$this->app->setContainer($this->container);
	}

	/**
	 * @test
	 */
	public function itAllowsAccessingToTheContainerServices()
	{
		// Arrange
		// Expect
		$this->container->expects($this->at(0))
			->method('get')
			->with('service_container')
			->will($this->returnValue($this->container));
		$this->container->expects($this->at(1))
			->method('get')
			->with('foo_service');

		// Act
		$this->app->getService('foo_service');
	}


	/**
	 * @test
	 */
	public function itAllowsAccessingToTheContainerParameters()
	{
		// Arrange
		// Expect
		$this->container->expects($this->at(0))
			->method('getParameterBag')
			->will($this->returnValue($this->container));
		$this->container->expects($this->at(1))
			->method('get')
			->with('foo_parameter');

		// Act
		$this->app->getParameter('foo_parameter');
	}

}

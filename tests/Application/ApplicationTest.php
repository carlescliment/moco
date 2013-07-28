<?php
namespace carlescliment\MoCo\Tests\Application;

use carlescliment\MoCo\Application\Application;


class ApplicationTest extends \PHPUnit_Framework_TestCase
{

	private $container;
	private $containerLocator;
	private $app;

	public function setUp()
	{
		$this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$this->app = new Application($this->container);
	}

	/**
	 * @test
	 */
	public function itAllowsAccessToTheContainer()
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
		$this->app->get('foo_service');
	}

}

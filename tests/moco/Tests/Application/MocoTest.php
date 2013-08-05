<?php

namespace moco\Tests\Application;

use carlescliment\moco\Application\Moco;


class MocoTest extends \PHPUnit_Framework_TestCase
{

	private $container;
	private $moco;

	public function setUp()
	{
		$this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$this->moco = new Moco;
		$this->moco->setContainer($this->container);
	}

	/**
	 * @test
	 */
	public function itAllowsAccessingToTheContainerServices()
	{
		// Arrange
		$this->container->expects($this->at(0))
			->method('get')
			->with('service_container')
			->will($this->returnValue($this->container));
			
		// Expect
		$this->container->expects($this->at(1))
			->method('get')
			->with('foo_service');

		// Act
		$this->moco->getService('foo_service');
	}


	/**
	 * @test
	 */
	public function itAllowsAccessingToTheContainerParameters()
	{
		// Arrange
		$this->container->expects($this->at(0))
			->method('getParameterBag')
			->will($this->returnValue($this->container));

		// Expect
		$this->container->expects($this->at(1))
			->method('get')
			->with('foo_parameter');

		// Act
		$this->moco->getParameter('foo_parameter');
	}


	/**
	 * @test
	 */
	public function itAllowsSettingParameters()
	{
		// Arrange
		$this->container->expects($this->at(0))
			->method('getParameterBag')
			->will($this->returnValue($this->container));

		// Expect
		$this->container->expects($this->at(1))
			->method('set')
			->with('foo_parameter', 50);

		// Act
		$this->moco->setParameter('foo_parameter', 50);
	}

}

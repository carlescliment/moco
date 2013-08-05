<?php

namespace moco\Tests\Application;

require_once dirname(__FILE__) . '/../Controller/SampleController.php';

use carlescliment\moco\Application\MocoBuilder,
	carlescliment\moco\Application\Moco;
use moco\Tests\Controller\SampleController;


class MocoBuilderTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 */
	public function itBuildsInstancesOfApplication()
	{
		// Arrange
		// Act
		$application = MocoBuilder::build(__DIR__ . '/../config', 'test');

		// Assert
		$this->assertTrue($application instanceof Moco);
	}

	/**
	 * @test
	 */
	public function itLoadsTheEnvironmentConfigurationFile()
	{
		// Arrange
		$application = MocoBuilder::build(__DIR__ . '/../config', 'test');

		// Act
		$environment_loaded = $application->getParameter('environment');

		// Assert
		$this->assertEquals('test', $environment_loaded);
	}

	/**
	 * @test
	 */
	public function itLoadsTheController()
	{
		// Arrange
		$application = MocoBuilder::build(__DIR__ . '/../config', 'test');

		// Act
		$controller = $application->getService('sample_controller');

		// Assert
		$this->assertTrue($controller instanceof SampleController);
	}

}
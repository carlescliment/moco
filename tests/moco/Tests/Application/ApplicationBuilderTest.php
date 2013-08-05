<?php

namespace moco\Tests\Application;

require_once dirname(__FILE__) . '/../Controller/SampleController.php';

use carlescliment\moco\Application\ApplicationBuilder,
	carlescliment\moco\Application\Application;
use moco\Tests\Controller\SampleController;


class ApplicationBuilderTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 */
	public function itBuildsInstancesOfApplication()
	{
		// Arrange
		// Act
		$application = ApplicationBuilder::build(__DIR__ . '/../config', 'test');

		// Assert
		$this->assertTrue($application instanceof Application);
	}

	/**
	 * @test
	 */
	public function itLoadsTheEnvironmentConfigurationFile()
	{
		// Arrange
		$application = ApplicationBuilder::build(__DIR__ . '/../config', 'test');

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
		$application = ApplicationBuilder::build(__DIR__ . '/../config', 'test');

		// Act
		$controller = $application->getService('sample_controller');

		// Assert
		$this->assertTrue($controller instanceof SampleController);
	}

}
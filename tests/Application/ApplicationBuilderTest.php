<?php

namespace tests\Application;

require_once dirname(__FILE__) . '/../Controller/SampleController.php';

use carlescliment\MoCo\Application\ApplicationBuilder,
	carlescliment\MoCo\Application\Application;
use tests\Controller\SampleController;


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
		$controller = $application->get('sample_controller');

		// Assert
		$this->assertTrue($controller instanceof SampleController);
	}

}
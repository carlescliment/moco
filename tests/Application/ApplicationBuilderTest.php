<?php

namespace carlescliment\MoCo\Tests\Application;

use carlescliment\MoCo\Application\ApplicationBuilder,
	carlescliment\MoCo\Application\Application;

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
}
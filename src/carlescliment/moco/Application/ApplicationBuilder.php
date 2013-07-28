<?php

namespace carlescliment\moco\Application;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use carlescliment\moco\DependencyInjection\CompilerPass\ControllerCompilerPass;

class ApplicationBuilder
{

	public static function build($config_path, $environment)
	{
		$container = new ContainerBuilder;
		$container->addCompilerPass(new ControllerCompilerPass);
		$loader = new YamlFileLoader($container, new FileLocator($config_path));
		$loader->load("config_$environment.yml");
		$container->compile();
		return new Application($container);
	}
}
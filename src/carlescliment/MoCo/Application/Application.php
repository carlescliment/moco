<?php

namespace carlescliment\MoCo\Application;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Application
{

	private $container;

	public function __construct(ContainerBuilder $container)
	{
		$this->container = $container;
	}


	public function get($service_name)
	{
		return $this->container->get('service_container')->get($service_name);
	}


	public function getParameter($parameter_name)
	{
		return $this->container->getParameterBag()->get($parameter_name);
	}

}
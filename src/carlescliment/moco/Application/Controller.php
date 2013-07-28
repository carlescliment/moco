<?php

namespace carlescliment\moco\Application;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
	Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Controller implements ContainerAwareInterface
{
	protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    }


	public function getService($service_name)
	{
		return $this->container->get('service_container')->get($service_name);
	}


	public function getParameter($parameter_name)
	{
		return $this->container->getParameterBag()->get($parameter_name);
	}

} 
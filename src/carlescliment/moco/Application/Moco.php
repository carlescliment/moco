<?php

namespace carlescliment\moco\Application;


use Symfony\Component\DependencyInjection\ContainerAwareInterface,
	Symfony\Component\DependencyInjection\ContainerInterface;

class Moco implements MocoInterface, ContainerAwareInterface
{

	private $container;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    	return $this;
    }


	public function getService($service_name)
	{
		return $this->container->get('service_container')->get($service_name);
	}


	public function getParameter($parameter_name)
	{
		return $this->container->getParameterBag()->get($parameter_name);
	}


    public function setParameter($parameter_name, $value)
    {
        $this->container->getParameterBag()->set($parameter_name, $value);
        return $this;
    }

}
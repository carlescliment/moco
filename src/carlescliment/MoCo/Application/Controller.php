<?php

namespace carlescliment\MoCo\Application;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
	Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Controller implements ContainerAwareInterface
{
	protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    }

} 
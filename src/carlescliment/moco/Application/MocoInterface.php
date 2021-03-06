<?php

namespace carlescliment\moco\Application;

interface MocoInterface
{
	public function getService($service_name);

    public function getParameter($parameter_name);

	public function setParameter($parameter_name, $value);
}
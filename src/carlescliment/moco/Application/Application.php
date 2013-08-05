<?php

namespace carlescliment\moco\Application;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

use carlescliment\moco\Exception\MocoException;

class Application extends Moco
{
    private $compiler_passes = array();
    private $running = false;


    public function addCompilerPass(CompilerPassInterface $compiler_pass)
    {
        if ($this->running) {
            throw new MocoException('You cannot add a compiler to a running application.');
        }
        $this->compiler_passes[] = $compiler_pass;
    }


    public function run()
    {
        $this->assertContainerIsSet();
        $this->addCompilerPassesToContainer();
        $this->container->compile();
        $this->running = true;
    }


    private function assertContainerIsSet()
    {
        if (is_null($this->container)) {
            throw new MocoException('You cannot run an application without container. Please, set it first.');
        }
    }

    private function addCompilerPassesToContainer()
    {
        foreach ($this->compiler_passes as $compiler_pass) {
            $this->container->addCompilerPass($compiler_pass);
        }
    }
}

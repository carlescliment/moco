<?php

namespace carlescliment\moco\Application;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use carlescliment\moco\Exception\MocoException;

class Application extends Moco
{
    private $compiler_passes = array();
    private $extensions = array();
    private $running = false;


    public function addCompilerPass(CompilerPassInterface $compiler_pass)
    {
        $this->assertNotRunning();
        $this->compiler_passes[] = $compiler_pass;
    }


    public function addExtension(ExtensionInterface $extension)
    {
        $this->assertNotRunning();
        $this->extensions[] = $extension;
    }


    public function run()
    {
        $this->assertContainerIsSet();
        $this->addCompilerPassesToContainer();
        $this->addExtensionsToContainer();
        $this->container->compile();
        $this->running = true;
        return $this;
    }


    private function assertContainerIsSet()
    {
        if (is_null($this->container)) {
            throw new MocoException('You cannot run an application without container. Please, set it first.');
        }
    }


    private function assertNotRunning()
    {
        if ($this->running) {
            throw new MocoException('You cannot add a compiler to a running application.');
        }
    }


    private function addCompilerPassesToContainer()
    {
        foreach ($this->compiler_passes as $compiler_pass) {
            $this->container->addCompilerPass($compiler_pass);
        }
    }


    private function addExtensionsToContainer()
    {
        foreach ($this->extensions as $extension) {
            $this->container->registerExtension($extension);
        }
    }
}

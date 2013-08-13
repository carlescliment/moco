<?php

namespace moco\Tests\Application;

use carlescliment\moco\Application\Application;


class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    private $app;

    public function setUp()
    {
        $this->app = new Application;
    }



    /**
     * @test
     * @expectedException carlescliment\moco\Exception\MocoException
     */
    public function itRaisesAnErrorWhenRunningWithoutAContainer()
    {
        // Arrange
        // Expect
        // Act
        $this->app->run();
    }


    /**
     * @test
     */
    public function itAllowsRegisteringANewCompiler()
    {
        // Arrange
        $container = $this->createApplicationContainer();
        $compiler_pass = $this->getMock('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
        $this->app->addCompilerPass($compiler_pass);

        // Expect
        $container->expects($this->once())
            ->method('addCompilerPass')
            ->with($compiler_pass);

        // Act
        $this->app->run();
    }


    /**
     * @test
     */
    public function itAllowsRegisteringANewExtension()
    {
        // Arrange
        $container = $this->createApplicationContainer();
        $extension = $this->getMock('Symfony\Component\DependencyInjection\Extension\ExtensionInterface');
        $this->app->addExtension($extension);

        // Expect
        $container->expects($this->once())
            ->method('registerExtension')
            ->with($extension);

        // Act
        $this->app->run();
    }


    /**
     * @test
     * @expectedException carlescliment\moco\Exception\MocoException
     */
    public function itRaisesAnErrorWhenAddingACompilerToARunningApplication()
    {
        // Arrange
        $container = $this->createApplicationContainer();
        $this->app->setContainer($container);
        $this->app->run();
        $compiler_pass = $this->getMock('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
        
        // Expect
        // Act
        $this->app->addCompilerPass($compiler_pass);
    }


    /**
     * @test
     * @expectedException carlescliment\moco\Exception\MocoException
     */
    public function itRaisesAnErrorWhenAddingAnExtensionToARunningApplication()
    {
        // Arrange
        $container = $this->createApplicationContainer();
        $this->app->setContainer($container);
        $this->app->run();
        $extension = $this->getMock('Symfony\Component\DependencyInjection\Extension\ExtensionInterface');
        
        // Expect
        // Act
        $this->app->addExtension($extension);
    }


    /**
     * @test
     */
    public function itCompilesTheContainerWhenRunning()
    {
        // Arrange
        $container = $this->createApplicationContainer();
        $compiler_pass = $this->getMock('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
        $this->app->addCompilerPass($compiler_pass);

        // Expect
        $container->expects($this->once())
            ->method('compile');

        // Act
        $this->app->run();
    }

    private function createApplicationContainer()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->app->setContainer($container);
        return $container;
    }

}

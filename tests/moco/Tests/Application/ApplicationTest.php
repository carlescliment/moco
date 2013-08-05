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
     * @expectedException carlescliment\moco\Exception\MocoException
     */
    public function itRaisesAnErrorWhenAddingACompilerPassWhenRunning()
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

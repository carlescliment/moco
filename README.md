MoCo
====

MoCo is a Model-Controller framework. Built on the Symfony Dependency Injection component, it lets you building HTTP-agnostic applications and focus in the bussiness logic your system needs. The goal is to obtain the better possible design by removing the annoyances of web-oriented frameworks. It is also not intended to generate views, albeit you could do it.

Once your bussiness logic has been properly coded and tested, you can then include your application in an HTTP framework that will handle the requests and provide responses in HTML, JSON or whatever you need.

## Installation

Create a directory that will contain your PHP application. Write a `composer.json` file in it. At first it will be something like this:

    {
        "name": "my/moco-app",
        "require": {
            "php": ">=5.3.2",
            "carlescliment/moco": "dev-master"
        },
        "autoload": {
            "psr-0": { "": "src/" }
        }
    }

Execute `php composer.phar update`. MoCo will be installed as a vendor.

## Building your app on MoCo

MoCo does not provide any standard to build your directory structure. Here is an example:

* Create a directory `src/Configuration/` inside your project.

* Create a new class `ApplicationBuilder` in `src/Configuration/ApplicationBuilder.php`.

    <?php

    namespace Configuration;
    
    use carlescliment\moco\Application\ApplicationBuilder as MocoBuilder;
    
    class ApplicationBuilder
    {
        public static function build($env = 'prod', $dir = null)
        {
            if (is_null($dir)) {
                $dir = __DIR__;
            }
            return MocoBuilder::build($dir, $env);
        }
    }


As you can see, MocoBuilder accepts two parameters, `$dir` and `$env`. `$dir` is the directory where the configuration files are. `$env` is the environment that will be used. We will talk about it later.


## Testing your first controller

Lets start from the beginning. In order to make tests run properly we need a few configuration files. First, create a phpunit.xml file in your root folder:


    <phpunit
        backupGlobals               = "false"
        backupStaticAttributes      = "false"
        colors                      = "true"
        convertErrorsToExceptions   = "true"
        convertNoticesToExceptions  = "true"
        convertWarningsToExceptions = "true"
        processIsolation            = "false"
        stopOnFailure               = "true"
        syntaxCheck                 = "false"
        bootstrap                   = "tests/bootstrap.php">
        <testsuites>
            <testsuite name="MoCo App Test Suite">
                <directory>tests/</directory>
            </testsuite>
        </testsuites>

        <filter>
            <whitelist>
                <directory>src/</directory>
            </whitelist>
        </filter>
    </phpunit>


Then, create the `tests/bootstrap.php` file to autoload the source code:

    <?php

    $loader = require __DIR__.'/../vendor/autoload.php';

    $loader->add('Test', __DIR__);

Perfect, now let's write the test. Create a folder `tests/Controller` and write a file `GreetingsControllerTest.php`.

    <?php

    namespace Test\Controller;

    use Configuration\ApplicationBuilder;

    class GreetingsControllerTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @test
         */
        public function itGreetsPeople()
        {
            $app = ApplicationBuilder::build('test')->run();

            $response = $app->getService('greetings_controller')->greet('Carles');

            $this->assertEquals('Hello Carles!', $response);
        }
    }


What the test is saying is that the application is able to say hello. Looking at the inner code we see that, first, we create an application instance, then execute the method `greet` of a 'greetings_controller' service and it should return a string greeting the person passed in the first argument.

Execute the test file by running the `phpunit` command from the root folder.

    E

    Time: 0 seconds, Memory: 3.75Mb

    There was 1 error:

    1) Test\Controller\GreetingsControllerTest::itGreetsPeople
    InvalidArgumentException: The file "config_test.yml" does not exist (in: /var/www/vhosts/moco-app/src/Configuration).


The test fails. It says it is looking for a file `config_test.yml` in `/var/www/vhosts/moco-app/src/Configuration` and it does not exist, right? Let's talk about environments.


## Environments

MoCo can run in many different environments. The most used environments are 'test', 'dev' and 'prod', although you could create an environment with the name you wish. Environments allow you to provide different configurations and behavours. It is very useful for testing, but also when you share the same codebase among different clients.

Let's satisfy what the test is asking for by writing an empty file `src/Configuration/config_test.yml`. Run the test again:


    E

    Time: 0 seconds, Memory: 4.00Mb

    There was 1 error:

    1) Test\Controller\GreetingsControllerTest::itGreetsPeople
    Symfony\Component\DependencyInjection\Exception\InvalidArgumentException: The service definition "greetings_controller" does not exist.

Hey, that's different. It is saying that we have not declared any service named "greetings_controller" in the container. MoCo has been designed to be consumed by other apps, so controllers have to be declared as services to be public.

To make this guide faster, we will make many different steps now.


## Building a public controller

First, let's organize or configuration file. Write a file in `src/Configuration/config.yml` with the following content:

    parameters:
        # declare your parameters here

    services:

        greetings_controller:
            class: Controller\GreetingsController


Modify the contents of `src/Configuration/config_test.yml`:

    imports:
        - { resource: "config.yml" }


If you execute the tests now you will see the following message:

    ReflectionException: Class Controller\GreetingsController does not exist

Cool, that's easy to solve. C'mon, write your controller in `src/Controller/GreetingsController.php`:

    <?php

    namespace Controller;

    class GreetingsController
    {
        public function greet($name)
        {
            return "Hello $name!";
        }
    }


## Advanced controllers

The controller we have written is enough for greeting people, but normally you will need a bit more in your logic. In fact, you will probably need to access to other services from your controller. MoCo lets you access to the dependency injection container by writing advanced controllers.


Change the declaration of the controller in `config.yml` and add a parameter:

    parameters:
        greet_clause: 'Hi'

    greetings_controller:
        class: Controller\GreetingsController
        tags:
            - { name: moco.controller }


Now modify your controller class:

    <?php

    namespace Controller;

    use carlescliment\moco\Application\Moco;

    class GreetingsController extends Moco
    {
        public function greet($name)
        {
            $greet_clause = $this->getParameter('greet_clause');
            return "$greet_clause $name!";
        }
    }


If you run the test now, you will see them fail and show the following message:

    1) Test\Controller\GreetingsControllerTest::itSaysHello
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
    -'Hello Carles!'
    +'Hi Carles!'

That means your controller is now aware of the environment configuration!


## Adding compilers and extensions

As your application grows, you will probably need to install third-party libs and expose them in the container. Fortunately, you can do it easily by adding compiler passes and extensions to the application before running it:

        $app = MocoBuilder::build($dir, $env);
        $app->addExtension(new MyExtension);
        $app->addCompilerPass(new SwiftMailerCompilerPass);
        $app->addCompilerPass(new DoctrineCompilerPass);
        $app->addCompilerPass(new EventDispatcherCompilerPass);
        $app->run();



## Embedding MoCo in your own framework

Once your bussiness logic is well-defined and coded, you can now require your application in your Symfony/Sylex/Drupal/CodeIgniter/whatever with composer. Just write the code needed to build the MocoApp and start using it the same way you do from tests.





MoCo
====

MoCo is a Model-Controller framework. Built on the Symfony Dependency Injection component, it lets you building HTTP-agnostic applications and focus in the bussiness logic your system needs. The goal is to obtain the better possible design by removing the annoyances of web-oriented frameworks. It is also not intendended to generate views, albeit you could do it.

Once your bussiness logic has been properly coded and tested, you can then include your application in an HTTP framework that will handle the requests and provide a front-end layer with the response in HTML, JSON or whatever you need.

# Installation

Create a directory that will contain your PHP application. Write a `composer.json` file in it. At first it will be something like this:

    {
        "name": "my/moco-app",
        "require": {
            "php": ">=5.3.2",
            "carlescliment/moco": "*"
        },
        "autoload": {
            "psr-0": { "": "src/" }
        }
    }

Execute `php composer.phar update`. MoCo will be installed as a vendor.

# Building your app on MoCo

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


# Your first controller.

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


Then, create the bootstrap file to autoload the source code:

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
        public function itSaysHello()
        {
            $app = ApplicationBuilder::build('test')->run();

            $response = $app->getService('greetings_controller')->hello('Carles');

            $this->assertEquals('Hello Carles!', $response);
        }
    }


Execute the test file by running the `phpunit` command from the root folder.

    E

    Time: 0 seconds, Memory: 3.75Mb

    There was 1 error:

    1) Test\Controller\GreetingsControllerTest::itSaysHello
    InvalidArgumentException: The file "config_test.yml" does not exist (in: /var/www/vhosts/moco-app/src/Configuration).


The test fails. It says it is looking for a file `config_test.yml` in `/var/www/vhosts/moco-app/src/Configuration` and it does not exist, right? Let's talk about environments.

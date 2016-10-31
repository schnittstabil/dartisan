<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\OutputFormatter;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $container = new Container();
        $container->set(OutputFormatter::class, function () {
            return function ($text) {
                return $text;
            };
        });

        $this->container = $container;
    }

    public function testExceptionInRunShouldBeHandled()
    {
        $this->container->set('argv', ['-', 'migrate']);
        $outputFormatter = $this->container->get(OutputFormatter::class);
        $sut = new FlawedCommand(
            new Args('foo', []),
            $outputFormatter
        );

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertRegExp('/<error>.*RuntimeException.*Flawed.*in.*FlawedCommand\.php/', $output);
        });

        $this->assertSame(2, $sut());
    }
}

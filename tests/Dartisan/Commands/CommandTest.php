<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;
use PHPUnit\Framework\TestCase;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\Output;
use Schnittstabil\Dartisan\OutputInterface;

class CommandTest extends TestCase
{
    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        $this->container = new Container();
        $this->container->set(OutputInterface::class, function () {
            return new Output();
        });
    }

    public function testExceptionInRunShouldBeHandled()
    {
        $this->container->set('argv', ['-', 'migrate']);
        $output = $this->container->get(OutputInterface::class);
        $sut = new FlawedCommand(
            new Args('foo', []),
            $output
        );

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertRegExp('/<error>.*RuntimeException.*Flawed.*in.*FlawedCommand\.php/', $output);
        });

        $this->assertSame(2, $sut());
    }
}

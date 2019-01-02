<?php

namespace Schnittstabil\Dartisan\Commands;

use PHPUnit\Framework\TestCase;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\Output;
use Schnittstabil\Dartisan\OutputInterface;

class MigrateMakeCommandTest extends TestCase
{
    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        array_map('unlink', glob('tests/temp/migrations/*'));

        $container = new Container();
        $container->set('migration-path', 'tests/temp/migrations');
        $container->set(OutputInterface::class, function () {
            return new Output();
        });

        $this->container = $container;
    }

    public function testMakeShouldCreateFiles()
    {
        $this->container->set('argv', ['-', 'make:migration', 'Noop']);

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $pattern = '#<info>Created Migration:</info> (?P<path>.*)#';
            $this->assertRegExp($pattern, $output);

            preg_match($pattern, $output, $matches);
            $file = $this->container->get('migration-path').'/'.$matches['path'].'.php';

            $this->assertFileExists($file);

            $this->assertContains('class Noop extends', file_get_contents($file));
        });

        $sut = $this->container->get('command');
        $this->assertSame(0, $sut());
    }
}

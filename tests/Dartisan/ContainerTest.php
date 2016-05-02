<?php

namespace Schnittstabil\Dartisan;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testContainerShouldReturnOutputFormatter()
    {
        $container = new Container();
        $sut = $container->get(OutputFormatter::class);
        $this->assertInstanceOf(OutputFormatter::class, $sut);
    }
}

<?php

namespace Schnittstabil\Dartisan;

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainerShouldReturnOutputFormatter()
    {
        $container = new Container();
        $sut = $container->get(OutputInterface::class);
        $this->assertInstanceOf(Output::class, $sut);
    }
}

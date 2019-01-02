<?php

namespace Schnittstabil\Dartisan;

use PHPUnit\Framework\TestCase;

class OutputFormatterTest extends TestCase
{
    public function testUnformatedTextShouldntBeAltered()
    {
        $sut = new OutputFormatter();
        $this->assertEquals('info', $sut('info'));
        $this->assertEquals('error', $sut('error'));
    }
}

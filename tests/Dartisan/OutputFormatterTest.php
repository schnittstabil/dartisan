<?php

namespace Schnittstabil\Dartisan;

class OutputFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testUnformatedTextShouldntBeAltered()
    {
        $sut = new OutputFormatter();
        $this->assertEquals('info', $sut('info'));
        $this->assertEquals('error', $sut('error'));
    }
}
